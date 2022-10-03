<?php

declare(strict_types=1);

namespace Src\Jobs\FindView;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Src\Broadcasting\Broadcast\Driver\RegularOrder;
use Src\Http\Resources\Driver\PassOrderResource;
use Src\Models\Order\Order;
use Src\Models\Order\OrderStatus;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Order\OrderServiceContract;

/**
 * Class SearchOrderForDriver
 * @package Src\Jobs
 */
class SearchOrderForDriver implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var PreorderContract
     */
    protected PreorderContract $preOrderContract;
    /**
     * @var GeocodeServiceContract
     */
    protected GeocodeServiceContract $geoService;
    /**
     * @var DriverContract
     */
    protected DriverContract $driverContract;
    /**
     * @var OrderContract
     */
    protected OrderContract $orderContract;
    /**
     * @var OrderServiceContract
     */
    protected OrderServiceContract $orderService;

    /**
     * Create a new job instance.
     *
     * @param  int  $driverId
     * @param $endPointCord
     * @param  array  $coordinates
     */
    public function __construct(protected int $driverId, protected $endPointCord, protected array $coordinates)
    {
    }

    /**
     * Execute the job.
     *
     * @param  PreorderContract  $preOrderContract
     * @param  GeocodeServiceContract  $geoService
     * @param  DriverContract  $driverContract
     * @param  OrderContract  $orderContract
     * @param  OrderServiceContract  $orderService
     * @return void
     */
    public function handle(
        PreorderContract $preOrderContract,
        GeocodeServiceContract $geoService,
        DriverContract $driverContract,
        OrderContract $orderContract,
        OrderServiceContract $orderService
    ): void {
        $this->preOrderContract = $preOrderContract;
        $this->geoService = $geoService;
        $this->driverContract = $driverContract;
        $this->orderContract = $orderContract;
        $this->orderService = $orderService;
        $now = now();

        while ($now->diffInSeconds(now()) <= 30) {
            $pre_orders = $this->getPreOrdersOrders();
            $orders = $this->getOrders();

            if (!$pre_orders->count() && !$orders->count()) {
                break;
            }

            if ($this->filterByEndPoint($pre_orders->merge($orders))) {
                break;
            }

            sleep(1);
        }
    }

    /**
     * @return Collection
     */
    protected function getPreOrdersOrders(): Collection
    {
        return $this->preOrderContract
            ->whereHas(
                'order',
                fn(Builder $query) => $query
                    ->whereJsonLength('to_coordinates', '>', 0)
                    ->where('status_id', '=', OrderStatus::ORDER_PENDING)
            )
            ->with([
                'order' => fn(BelongsTo $query) => $query
                    ->select(['order_id', 'from_coordinates', 'to_coordinates', 'car_class_id', 'franchisee', 'car_option'])
            ])
            ->findAll()
            ->filter(fn($item) => Carbon::parse($item->start_time)->diffInMinutes(now()) < 20);
    }

    /**
     * @return Collection
     */
    protected function getOrders(): Collection
    {
        return $this->orderContract
            ->whereDoesntHave('preorder')
            ->whereDoesntHave('ordering_shipments')
            ->whereJsonLength('to_coordinates', '>', 0)
            ->findWhere(
                ['status_id', '=', OrderStatus::getStatusId(OrderStatus::ORDER_PENDING)],
                ['order_id', 'from_coordinates', 'to_coordinates', 'car_class_id', 'franchisee', 'car_option']
            );
    }

    /**
     * @param $orders
     * @return bool
     */
    protected function filterByEndPoint($orders): bool
    {
        foreach ($orders as $order) {
            $result_from[$order->order_id] = $this->geoService->calculateDistanceFromRoad($order->from_coordinates, $this->road);
            $result_to[$order->order_id] = $this->geoService->calculateDistanceFromRoad($order->to_coordinates, $this->road);
        }

        $res_from_distance = array_keys($result_from, min($result_from));
        $res_to_distance = array_keys($result_to, min($result_to));

        $min_from_distance = $result_from[$res_from_distance[0]];
        $min_to_distance = $result_from[$res_to_distance[0]];

        if (($min_from_distance === $min_to_distance)/*@todo &&*/ || ($min_from_distance <= 250 && $min_to_distance <= 250)) {
            $received_order = $this->orderContract->find(
                $min_from_distance,
                [
                    'order_id',
                    'car_class_id',
                    'order_type_id',
                    'payment_type_id',
                    'address_from',
                    'address_to',
                    'from_coordinates',
                    'to_coordinates'
                ]
            );

            $this->sendOrder($received_order);

            return true;
        }

        return false;
    }

    /**
     * @param  Order  $order
     */
    protected function sendOrder(Order $order): void
    {
        $driver = $this->driverContract->find($this->driverId, ['driver_id', 'car_id', 'current_franchise_id', 'phone']);
        $created_order = $this->orderService->createOrderForDriver($this->driverId, $order->order_id, $order->address_from);

        RegularOrder::broadcast($driver, new PassOrderResource($created_order));
    }
}
