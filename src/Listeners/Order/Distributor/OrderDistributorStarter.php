<?php

declare(strict_types=1);

namespace Src\Listeners\Order\Distributor;

use Carbon\Carbon;
use JsonException;
use ReflectionException;
use Src\Broadcasting\Broadcast\Client\NonDriverEvent;
use Src\Broadcasting\Broadcast\Driver\OrderTimeOut;
use Src\Broadcasting\Broadcast\Driver\RegularOrder;
use Src\Events\Driver\DriverUpdateEvent;
use Src\Events\Order\DistributeOrder;
use Src\Exceptions\Lexcept;
use Src\Exceptions\Order\ExitNonDriverException;
use Src\Exceptions\Order\OrderCanceledInSearchDriverException;
use Src\Http\Resources\Driver\PassOrderResource;
use Src\Listeners\Order\Distributor\Contracts\RealizingInterface;
use Src\Listeners\Order\Distributor\Core\BaseOrderDistributor;
use Src\Listeners\Order\Distributor\Runners\AutomateDistributor;
use Src\Listeners\Order\Distributor\Runners\CommonDistributor;
use Src\Listeners\Order\Distributor\Runners\PreOrderDistributor;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\OrderStatus;

/**
 * Class ClientClientWebOrderListener
 *
 * @package Src\Listeners\Order
 */
class OrderDistributorStarter extends BaseOrderDistributor implements RealizingInterface
{
    /**
     * @var DistributeOrder|null
     */
    public ?DistributeOrder $payload;
    /**
     * @var int
     */
    protected int $searchCycle = 0;

    /**
     * Handle the event.
     *
     * @param  DistributeOrder  $event
     *
     * @return void
     * @throws OrderCanceledInSearchDriverException
     * @throws ReflectionException
     */
    public function handle(DistributeOrder $event): void
    {
        $this->payload = $event;

        $this->setType(true);
        $this->runner();
        $this->payload = null;
    }

    /**
     * @param  mixed  ...$mixed
     * @throws OrderCanceledInSearchDriverException
     * @throws ReflectionException
     */
    public function runner(...$mixed): void
    {
        if ($this->orderService->orderHasCanceled($this->payload->order->order_id, true)) {
            return;
        }

        if ($this->isOrderManual()) {
            $common_order = CommonDistributor::decorate($this);
            $common_order->search();
            $common_order->annul();
            return;
        }

        if (!empty($this->payload->orderData['preorder'])
            && !$this->preOrderContract->where('order_id', '=', $this->payload->order->order_id)->where('skip', '=', true)->exists()
        ) {
            $pre_order = PreOrderDistributor::decorate($this);
            $pre_order->search();
            $pre_order->annul();
            return;
        }

        $automate = AutomateDistributor::decorate($this);
        $automate->search();
        $automate->annul();
    }

    /**
     * @return int
     */
    public function getPreorderDiff(): int
    {
        return Carbon::parse($this->payload->orderData['preorder']['time'], now()->timezone)->diffInMinutes(now(now()->timezone));
    }

    /**
     * @param  int  $rating
     * @param  float  $assessment
     * @param  int  $radius
     * @param  int  $sub_minute
     * @return bool
     * @throws ExitNonDriverException
     * @throws OrderCanceledInSearchDriverException
     * @throws ReflectionException
     * @throws JsonException
     * @throws Lexcept
     * @noinspection MultipleReturnStatementsInspection
     */
    final public function twisting(int $rating = 310, float $assessment = 3.0, int $radius = 1000, int $sub_minute = 60): bool
    {
        ++$this->searchCycle;
        $sub = 0 > $sub_minute || !$sub_minute ? null : now()->subMinutes($sub_minute);

        if (!$this->baseCriterionSearch()) {
            return false;
        }

        if (!$this->selectedDrivers || !$this->selectedDrivers->count()) {
            $this->getDrivers($rating, $assessment, $radius, $sub, 4, $this->rejectedDrivers);
        }

        if (15 < $this->searchCycle || $this->selectedDrivers->count() < 1) {
            if (81 > $rating && !$this->commonContract->where('order_id', '=', $this->payload->order->order_id)->exists()) {
                $common_order = CommonDistributor::decorate($this);
                $common_order->search();
                $common_order->annul();

                return false;
            }

            return $this->twisting($rating - 5, $assessment - 0.1, $radius + 200, $sub_minute - 15);
        }

        $filtered_driver = $this->getDriversFilter($this->selectedDrivers, (string)config('nyt.driver_search_thread_radius'),
            (string)config('nyt.allowed_duration_driver'));

        if ($filtered_driver) {
            $this->broadcastDriverReceives($filtered_driver, $filtered_driver->road_distance, $filtered_driver->road_duration, $filtered_driver->road_point);

            DriverUpdateEvent::dispatch($filtered_driver);

            if (!$this->setShippedOrders($filtered_driver)) {
                return $this->twisting($rating - 5, $assessment - 0.1, $radius + 250, $sub_minute - 15);
            }

            return true;
        }

        return $this->twisting($rating - 10, $assessment - 0.1, $radius + 300, $sub_minute - 15);
    }

    /**
     * @param $driver
     * @param $distance
     * @param $duration
     * @param $points
     * @throws OrderCanceledInSearchDriverException
     */
    public function broadcastDriverReceives($driver, $distance, $duration, $points): void
    {
        $passed = now()->addMilliseconds(100);

        $create_order = $this->orderService->createOrderForDriver(
            $driver->driver_id,
            $this->payload->order->order_id,
            $this->payload->order->address_from,
            $distance,
            $duration,
            $points
        );
        $order = array_merge($create_order, ['client_phone' => $this->client()->phone]);

        RegularOrder::broadcast($driver, new PassOrderResource($order));

        while ($passed->diffInSeconds(now()) < (int)config('nyt.driver_response_time') + 0.5) {
            if ($this->orderService->orderHasCanceled($this->payload->order->order_id, true)
                || $this->driverService->driverHasAcceptOrder($driver->driver_id)
                || $this->driverService->driverIsRejectOrder($driver->driver_id)
                || $this->isOrderManualShipped()) {
                break;
            }

            usleep(300000);
        }
    }

    /**
     * @param $driver
     * @return bool
     */
    public function setShippedOrders($driver): bool
    {
        $accepted_order = $this->driverService->driverHasAcceptOrder($driver->driver_id);

        if (!$accepted_order) {
            $shipped = $this->shippedContract->getCurrentPendingShipped($this->payload->order->order_id, $driver->driver_id);

            if ($shipped) {
                $rating = $this->ratingService->setDriverRating($driver->driver_id, $this->payload->order->order_id,
                    $shipped->estimated_rating->remove_patterns['ids']);

                $update_shipment = $this->shippedContract->update($shipped->{$shipped->getKeyName()}, [
                    'status_id' => OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_REJECTED),
                    'current' => 0
                ]);

                $update_shipment ? OrderTimeOut::broadcast($driver, $rating->get('rating')) : null;
            }

            foreach ($this->selectedDrivers as $key => $selected) {
                if ($selected && $selected->driver_id === $driver->driver_id) {
                    unset($this->selectedDrivers[$key]);
                }
            }
        }

        return $accepted_order;
    }

    /**
     *
     */
    public function driverNotFound(): void
    {
        $this->orderContract
            ->where('order_id', '=', $this->payload->order->order_id)
            ->updateSet(['status_id' => OrderStatus::getStatusId(OrderStatus::ORDER_CANCELED)]);

        $this->clientContract
            ->where('client_id', '=', $this->client()->client_id)
            ->updateSet(['in_order' => false]);

        NonDriverEvent::dispatch($this->client(), trans('messages.none_driver_create_order'));
    }
}
