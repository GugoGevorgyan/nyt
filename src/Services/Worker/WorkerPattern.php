<?php

declare(strict_types=1);

namespace Src\Services\Worker;

use Src\Broadcasting\Broadcast\Driver\CommonOrderEvent;
use Src\Exceptions\Lexcept;
use Src\Http\Resources\Driver\PassOrderResource;
use Src\Models\Order\OrderShippedStatus;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderAttach\OrderAttachContract;
use Src\Repositories\OrderCommon\OrderCommonContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\RatingPointService\RatingPointServiceContract;
use Src\ServicesCrud\Order\OrderCrudContract;
use Throwable;

/**
 *
 */
final class WorkerPattern
{
    public function __construct(
        protected DriverContract $driverContract,
        protected OrderServiceContract $orderService,
        protected OrderContract $orderContract,
        protected GeocodeServiceContract $geoService,
        protected OrderShippedDriverContract $shippedContract,
        protected RatingPointServiceContract $ratingService,
        protected PreorderContract $preorderContract,
        protected OrderAttachContract $orderAttachContract,
        protected OrderCommonContract $commonContract,
        protected OrderCrudContract $orderCrud
    ) {
    }

    /**
     * @param $order_id
     * @param $driver_id
     * @return bool
     * @throws Lexcept
     */
    public function preparePreorderFirstEtap($order_id, $driver_id): bool
    {
        $driver = $this->driverContract->find($driver_id, ['driver_id', 'car_id', 'phone', 'current_franchise_id', 'lat', 'lut']);
        $order = $this->orderContract
            ->with([
                'preorder' => fn($query) => $query->select(['order_id', 'preorder_id']),
                'current_shipped' => fn($query) => $query->select(['driver_id', 'order_id', 'order_shipped_driver_id', 'on_way_hash']),
                'common' => fn($query) => $query
                    ->where('active', '=', true)
                    ->select(['order_id', 'driver', 'filter_type', 'distance', 'order_common_id']),
                'shipped' => fn($query) => $query
                    ->where('driver_id', '=', $driver_id)
                    ->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
                    ->select(['driver_id', 'order_id', 'order_shipped_driver_id', 'on_way_hash', 'status_id']),
            ])
            ->find($order_id, ['order_id', 'from_coordinates', 'address_from', 'to_coordinates', 'to_coordinates', 'comments']);

        if (!$driver || !$order) {
            throw new Lexcept('invalid data', 500);
        }

        if ($order->current_shipped) {
            throw new Lexcept('! Order is send to driver, please wait', 500);
        }

        if ($order->common) {
            $drivers = $this->driverContract
                ->findWhereIn(['driver_id', $order->common->driver['ids']], ['driver_id', 'car_id', 'phone', 'current_franchise_id']);

            $common_output = new PassOrderResource(['order_id' => $order_id]);

            foreach ($drivers as $driver) {
                CommonOrderEvent::broadcast($driver, $common_output, 'delete');
            }

            $this->commonContract->update($order->common->order_common_id, ['active' => false]);
        }

        $tik_time = $order->preorder && $order->shipped ? 31 : 11;

        if (!$this->orderCrud->orderAttachToDriver($order_id, $driver_id, $tik_time)) {
            return false;
        }

        return true;
    }
}
