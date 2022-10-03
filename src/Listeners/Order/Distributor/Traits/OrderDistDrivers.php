<?php

declare(strict_types=1);


namespace Src\Listeners\Order\Distributor\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Src\Models\Driver\DriverStatus;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\OrderStatus;
use Staudenmeir\EloquentJsonRelations\Relations\HasManyJson;

/**
 * Trait OrderDistributor
 * @package Src\Core\Traits
 */
trait OrderDistDrivers
{
    /**
     * @var Collection|null
     */
    public ?Collection $selectedDrivers = null;
    /**
     * @var array|null
     */
    public ?array $rejectedDrivers = [];

    /**
     * @param $rating
     * @param $assessment
     * @param  int  $radius
     * @param  Carbon|null  $sub
     * @param  int  $limit
     * @param  array  $reject_driver_ids
     * @return Collection
     */
    final public function getDrivers($rating, $assessment, int $radius = 1, Carbon $sub = null, int $limit = 3, array $reject_driver_ids = []): Collection
    {
        $reject_driver_ids = array_unique($reject_driver_ids);

        $find = [
            'driver_id',
            'car_id',
            'selected_class',
            'selected_option',
            'current_franchise_id',
            'current_status_id',
            'online',
            'is_ready',
            'rating',
            'mean_assessment',
            'lat',
            'lut',
            'phone',
            'distance'
        ];

        $check_radius = $radius && method_exists($this, 'detectClientCoordinates');

        if ($check_radius) {
            $client_cord = $this->detectClientCoordinates();
        }

        $drivers = $this->driverContract
            ->where('online', '=', true)
            ->where('is_ready', '=', true)
            ->where('rating', '>=', $rating)
            ->where('mean_assessment', '>=', $assessment)
            ->has('current_active_waybill')
            ->has('active_contract')
            ->doesntHave('locked')
            ->whereJsonContains('selected_class->ids', $this->payload->order->car_class_id)
            ->whereDoesntHave('orders_shipment', fn(Builder $favorite) => $favorite->where('order_id', '=', $this->payload->order->order_id))
            ->whereDoesntHave('orders_shipment', fn(Builder $favorite) => $favorite->where('current', '=', true)->where('common', '=', false))
            ->whereHas('status', fn(Builder $status_query) => $status_query->where('status', '=', DriverStatus::DRIVER_IS_FREE))
            ->when($reject_driver_ids,
                fn(Builder $query) => $query->whereNotIn('driver_id', $reject_driver_ids))
            ->when(
                $this->driverType,
                fn($query) => $query->whereHas('type', fn(Builder $type) => $type->whereIn('driver_types.driver_type_id', $this->driverType))
            )
            ->when(
                !empty($this->franchiseeIds),
                fn(Builder $query) => $query->whereIn('current_franchise_id', $this->franchiseeIds['ids'])
            )
            ->when(
                !empty($this->payload->order->car_option['ids']),
                fn(Builder $query) => $query->whereJsonContains('selected_option->ids', $this->payload->order->car_option['ids'])
            )
            ->when(
                $sub,
                fn($query) => $query->whereDoesntHave(
                    'orders',
                    fn(Builder $query) => $query->whereBetween('created_at', [$sub, now()->format('Y-m-d H:i:s.u')])
                )
            )
            ->when(
                $check_radius,
                fn($query) => $query
                    ->cordDistance($client_cord['lat'], $client_cord['lut'])
                    ->havingRaw("distance < $radius")
                    ->orderBy('distance')
            )
            ->with([
                'orders_shipment' => fn(HasMany $query) => $query
                    ->select(['order_shipped_driver_id', 'driver_id', 'order_id', 'status_id', 'current', 'common', 'late', 'created_at'])
                    ->orderByDesc('order_shipped_driver_id')
                    ->limit(2),
                'common_orders' => fn(HasManyJson $query) => $query
                    ->whereNotNull('accepted')
                    ->where('accept', '=', true)
                    ->where('manual', '=', false)
                    ->whereHas('order', fn(Builder $query) => $query->where('status_id', '=', OrderStatus::ORDER_PENDING))
                    ->with([
                        'preorder' => fn(BelongsTo $query) => $query
                            ->where('active', '=', true)
                            ->where('time', '>=', now(session('app_system.timezone')))
                            ->whereHas('shipped', fn(Builder $query) => $query->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED))
                            ->select(['preorder_id', 'order_id', 'driver', 'create_time', 'time', 'accept', 'accepted', 'distribution_start'])
                    ])
                    ->select(['driver', 'order_id']),
            ])
            ->limit($limit)
            ->findAll($find);

        if (!empty($this->payload->order->to_coordinates) && 3 < $this->searchCycle && odd($this->searchCycle)) {
            $drivers->merge($this->addressedDriver($radius));
        }

        if (property_exists($this, 'selectedDrivers')) {
            $this->selectedDrivers = $drivers;
        }

        return $drivers;
    }

    /**
     * @param  int  $radius
     * @return Collection
     */
    private function addressedDriver(int $radius = 1000): Collection
    {
        $find = [
            'driver_id',
            'car_id',
            'selected_class',
            'selected_option',
            'current_franchise_id',
            'current_status_id',
            'online',
            'is_ready',
            'rating',
            'mean_assessment',
            'lat',
            'lut',
            'phone',
            'distance'
        ];

        return $this->driverContract
            ->whereJsonContains('selected_class->ids', $this->payload->order->car_class_id)
            ->whereDoesntHave('orders_shipment', fn(Builder $favorite) => $favorite->where('order_id', '=', $this->payload->order->order_id))
            ->whereDoesntHave('orders_shipment', fn(Builder $favorite) => $favorite->where('current', '=', true)->where('common', '=', false))
            ->whereHas('addresses', fn(Builder $query) => $query
                ->where('active', '=', true)
                ->select(['driver_address_id', 'driver_id', 'lat', 'lut', 'active', 'target_road']))
            ->has('current_active_waybill')
            ->when(
                $this->driverType,
                fn($query) => $query->whereHas('type', fn(Builder $type) => $type->whereIn('driver_types.driver_type_id', $this->driverType))
            )
            ->when(
                !empty($this->payload->order->car_option['ids']),
                fn(Builder $query) => $query->whereJsonContains('selected_option->ids', $this->payload->order->car_option['ids'])
            )
            ->cordDistance($this->detectClientCoordinates()['lat'], $this->detectClientCoordinates()['lut'])
            ->havingRaw("distance <= $radius")
            ->findAll($find)
            ->map(function ($driver) {
                foreach ($driver->addresses as $key => $address) {
                    $result_from[$key] = $this->geoService->calculateDistanceFromRoad($this->payload->order->from_coordinates, $address->target_road);
                    $result_to[$key] = $this->geoService->calculateDistanceFromRoad($this->payload->order->to_coordinates, $address->target_road);
                }

                $res_from_distance = array_keys($result_from, min($result_from));
                $res_to_distance = array_keys($result_to, min($result_to));

                $min_from_distance = $result_from[$res_from_distance[0]];
                $min_to_distance = $result_from[$res_to_distance[0]];

                if (($min_from_distance === $min_to_distance)/*@todo &&*/ || ($min_from_distance <= 300 && $min_to_distance <= 300)) {
                    return $driver;
                }

                return [];
            });
    }

    /**
     * @param $rating
     * @param $assessment
     * @param  int  $radius
     * @param  int  $limit
     * @param  array  $reject_driver_ids
     * @param  bool  $free
     * @param  Carbon|null  $interval
     * @return Collection
     */
    final public function getPreOrderDrivers(
        $rating,
        $assessment,
        int $radius = 0,
        int $limit = 3,
        array $reject_driver_ids = [],
        bool $free = true,
        Carbon $interval = null
    ): Collection {
        $find = [
            'driver_id',
            'car_id',
            'selected_class',
            'selected_option',
            'current_franchise_id',
            'current_status_id',
            'online',
            'is_ready',
            'rating',
            'mean_assessment',
            'lat',
            'lut',
            'phone',
        ];

        $check_radius = $radius && method_exists($this, 'detectClientCoordinates');

        if ($check_radius) {
            $client_cord = $this->detectClientCoordinates();
        }

        return $this->driverContract
            ->where('online', '=', true)
            ->where('is_ready', '=', true)
            ->where('rating', '>=', $rating)
            ->where('mean_assessment', '>=', $assessment)
            ->has('current_active_waybill')
            ->has('active_contract')
            ->doesntHave('locked')
            ->whereJsonContains('selected_class->ids', $this->payload->order->car_class_id)
            ->whereDoesntHave('orders_shipment', fn(Builder $favorite) => $favorite->where('order_id', '=', $this->payload->order->order_id))
            ->when($interval, fn(Builder $query) => $query
                ->whereDoesntHave('common_orders', fn($query) => $query
                    ->where('accept', '=', true)
                    ->whereHas('preorder', fn(Builder $query) => $query
                        ->where('active', '=', true)
                        ->where('distribution_start', '<=', $interval)
                        ->where('distribution_start', '>=', $interval)))
            )
            ->when($free,
                fn($query) => $query
                    ->whereHas('status', fn(Builder $status_query) => $status_query->where('status', '=', DriverStatus::DRIVER_IS_FREE))
                    ->whereDoesntHave('orders_shipment', fn(Builder $favorite) => $favorite->where('current', '=', true))
            )
            ->when($reject_driver_ids,
                fn(Builder $query) => $query->whereNotIn('driver_id', $reject_driver_ids))
            ->when(
                !empty($this->franchiseeIds),
                fn(Builder $query) => $query->whereIn('current_franchise_id', $this->franchiseeIds['ids'])
            )
            ->when(
                !empty($this->payload->order->car_option['ids']),
                fn(Builder $query) => $query->whereJsonContains('selected_option->ids', $this->payload->order->car_option['ids'])
            )
            ->when(
                $check_radius && $client_cord,
                fn($query) => $query
                    ->cordDistance($client_cord['lat'], $client_cord['lut'])
                    ->havingRaw("distance <= $radius")
                    ->orderBy('distance')
            )
            ->limit($limit)
            ->findAll($find);
    }
}
