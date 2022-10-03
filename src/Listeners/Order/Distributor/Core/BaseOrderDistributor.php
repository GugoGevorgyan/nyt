<?php

declare(strict_types=1);


namespace Src\Listeners\Order\Distributor\Core;

use Illuminate\Database\Eloquent\Builder;
use Src\Broadcasting\Broadcast\Driver\ClientOrderCancel;
use Src\Core\Enums\ConstDriverType;
use Src\Exceptions\Order\OrderCanceledInSearchDriverException;
use Src\Listeners\Order\Distributor\Traits\OrderDistDrivers;
use Src\Listeners\Order\Distributor\Traits\OrderDistFilters;
use Src\Models\Order\OrderShippedStatus;
use Src\Repositories\CanceledOrder\CanceledOrderContract;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\Franchisee\FranchiseContract;
use Src\Repositories\FranchiseeOption\FranchiseOptionContract;
use Src\Repositories\InitialOrderData\InitialOrderDataContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCommon\OrderCommonContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;
use Src\Repositories\Tariff\TariffContract;
use Src\Repositories\TariffBehind\TariffBehindContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\RatingPointService\RatingPointServiceContract;

/**
 * Class BaseOrderDistributor
 * @package Src\Listeners\Order
 */
abstract class BaseOrderDistributor
{
    use OrderDistDrivers;
    use OrderDistFilters;

    /**
     * @var array
     */
    protected array $driverType = [];
    /**
     * @var array
     */
    protected array $franchiseeIds = [];

    /**
     * Create the event listener.
     *
     * @param  PreorderContract  $preOrderContract
     * @param  OrderShippedDriverContract  $shippedContract
     * @param  RatingPointServiceContract  $ratingService
     * @param  OrderCommonContract  $commonContract
     * @param  CanceledOrderContract  $canceledOrderContract
     * @param  OrderServiceContract  $orderService
     * @param  OrderContract  $orderContract
     * @param  ClientContract  $clientContract
     * @param  InitialOrderDataContract  $initialOrderContract
     * @param  TariffContract  $tariffContract
     * @param  TariffBehindContract  $tariffBehindMkadContract
     * @param  DriverContract  $driverContract
     * @param  ClientServiceContract  $clientService
     * @param  DriverServiceContract  $driverService
     * @param  GeocodeServiceContract  $geoService
     * @param  SystemWorkerContract  $workerContract
     * @param  FranchiseContract  $franchiseContract
     * @param  FranchiseOptionContract  $franchiseOptionContract
     */
    public function __construct(
        public PreorderContract $preOrderContract,
        public OrderShippedDriverContract $shippedContract,
        public RatingPointServiceContract $ratingService,
        public OrderCommonContract $commonContract,
        public CanceledOrderContract $canceledOrderContract,
        public OrderServiceContract $orderService,
        public OrderContract $orderContract,
        public ClientContract $clientContract,
        public InitialOrderDataContract $initialOrderContract,
        protected TariffContract $tariffContract,
        protected TariffBehindContract $tariffBehindMkadContract,
        protected DriverContract $driverContract,
        protected ClientServiceContract $clientService,
        protected DriverServiceContract $driverService,
        protected GeocodeServiceContract $geoService,
        protected SystemWorkerContract $workerContract,
        protected FranchiseContract $franchiseContract,
        protected FranchiseOptionContract $franchiseOptionContract,
    ) {
    }

    /**
     * @return object|null
     */
    protected function client(): ?object
    {
        return $this->clientService->getOrderedClientData($this->payload->order->order_id, ['client_id', 'phone']);
    }

    /**
     * @return bool
     * @throws OrderCanceledInSearchDriverException
     * @noinspection MultipleReturnStatementsInspection
     */
    protected function baseCriterionSearch(): bool
    {
        if ($this->isOrderManualShipped()) {
            return false;
        }

        if (1 < $this->searchCycle) {
            sleep(2);
        }

        if ($this->isOrderManualShipped()) {
            return false;
        }

        if ($this->payload->timer->diffInSeconds(now()) >= config('nyt.taxi_search_time')) {
            $this->driverNotFound();
            return false;
        }

        if ($this->orderService->orderHasCanceled($this->payload->order->order_id, true)) {
            return false;
        }

        $accept_shipped = $this->shippedContract
            ->where('order_id', '=', $this->payload->order->order_id)
            ->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
            ->exists();

        if ($accept_shipped) {
            return false;
        }

        if (5 < $this->searchCycle) {
            $this->driverType = [
                ConstDriverType::CORPORATE()->getValue(),
                ConstDriverType::ROLL()->getValue(),
                ConstDriverType::TENANT()->getValue(),
                ConstDriverType::AGGREGATOR()->getValue(),
            ];
            $this->franchiseeIds = [];
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function isOrderManualShipped(): bool
    {
        $manual = $this->isOrderManual();
        $driver = $this->orderContract->getOrderedShippedDriver($this->payload->order->order_id, ['driver_id', 'car_id', 'phone', 'current_franchise_id']);

        if ($manual && $driver) {
            $order = $this->orderContract->find($this->payload->order->order_id, ['order_id', 'address_from', 'from_coordinates']);
            ClientOrderCancel::broadcast($driver, $order);
            $this->shippedContract->updateOrCreate(
                ['order_id', '=', $this->payload->order->order_id, 'driver_id', '=', $driver->driver_id],
                ['current' => false, 'status_id' => OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_MANUAL)]
            );
        }

        return $manual;
    }

    /**
     * @return bool
     */
    protected function isOrderManual(): bool
    {
        return $this->orderContract
            ->where('order_id', '=', $this->payload->order->order_id)
            ->whereHas('common', fn(Builder $query) => $query->where('manual', '=', true))
            ->exists('order_id');
    }

    /**
     * @return array|null
     */
    protected function detectClientCoordinates(): ?array
    {
        if ($this->payload->order->customer_type !== $this->clientContract->getMap()) {
            return ['lat' => $this->payload->order->from_coordinates['lat'], 'lut' => $this->payload->order->from_coordinates['lut']];
        }

        $client = $this->clientContract->find($this->payload->order->client_id, ['client_id']);

        if ($client) {
            return $this->clientService->getCorrectCoordinate($client->client_id, $client->getMap(), $this->payload->order->order_id);
        }

        return null;
    }
}
