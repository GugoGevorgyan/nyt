<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Driver\Driver;
use Src\Models\Tariff\Tariff;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class OrderOnWayRoad
 *
 * @package Src\Models\Order
 * @property int $order_process_data_id
 * @property int $order_id
 * @property array $route
 * @property float $distance
 * @property int $duration
 * @property int|null $selected
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereOrderProcessDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $order_on_way_road_id
 * @property int $shipment_driver_id
 * @property array|null $real_road
 * @property int|null $speed
 * @property Carbon|null $cord_updated
 * @property Carbon|null $deleted_at
 * @property-read Driver $driver
 * @property-read OrderShippedDriver $shipment_driver
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static Builder|OrderOnWayRoad onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereCordUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereOrderOnWayRoadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereRealRoad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereShipmentDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereSpeed($value)
 * @method static Builder|OrderOnWayRoad withTrashed()
 * @method static Builder|OrderOnWayRoad withoutTrashed()
 * @property-read OrderProcess|null $process
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $order_late
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereOrderLate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class OrderOnWayRoad extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'order_on_way_roads';
    /**
     * @var string
     */
    protected $primaryKey = 'order_on_way_road_id';
    /**
     * @var array
     */
    protected $fillable = ['shipment_driver_id', 'route', 'real_road', 'distance', 'duration', 'selected'];
    /**
     * @var array
     */
    protected $casts = ['route' => 'array', 'real_road' => 'array', 'distance' => 'float', 'duration' => 'integer', 'cord_updated' => 'datetime'];
    /**
     * @var string
     */
    protected string $map = 'onWayRoad';

    /**
     * @return BelongsTo
     */
    public function shipment_driver(): BelongsTo
    {
        return $this->belongsTo(OrderShippedDriver::class, 'shipment_driver_id');
    }

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }


    /**
     * @return BelongsToThrough
     */
    public function order_initial(): BelongsToThrough
    {
        return $this->belongsToThrough(
            OrderInitialData::class,
            [Order::class, OrderShippedDriver::class],
            null,
            '',
            [OrderInitialData::class => 'initial_data_id', Order::class => 'order_id', OrderShippedDriver::class => 'shipment_driver_id']
        );
    }

    /**
     * @return BelongsToThrough
     */
    public function order_initial_tariff(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Tariff::class,
            [OrderInitialData::class, Order::class, OrderShippedDriver::class],
            null,
            '',
            [
                Tariff::class => 'initial_tariff_id',
                OrderInitialData::class => 'initial_data_id',
                Order::class => 'order_id',
                OrderShippedDriver::class => 'shipment_driver_id'
            ]
        );
    }

    /**
     * @return BelongsToThrough
     */
    public function order_second_tariff(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Tariff::class,
            [OrderInitialData::class, Order::class, OrderShippedDriver::class],
            null,
            '',
            [
                Tariff::class => 'second_tariff_id',
                OrderInitialData::class => 'initial_data_id',
                Order::class => 'order_id',
                OrderShippedDriver::class => 'shipment_driver_id'
            ]
        );
    }
}
