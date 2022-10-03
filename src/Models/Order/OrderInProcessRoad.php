<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use JsonException;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Driver\Driver;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class OrderInProcessRoad
 *
 * @package Src\Models\Order
 * @property int $order_in_process_road_id
 * @property array|mixed $route
 * @property int $shipment_driver_id
 * @property float|null $distance
 * @property float|null $duration
 * @property int $selected
 * @property array|mixed $real_road
 * @property int|null $speed
 * @property Carbon|null $cord_updated
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Driver $driver
 * @property-read OrderShippedDriver $shipment_driver
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad newQuery()
 * @method static Builder|OrderInProcessRoad onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereCordUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereOrderInProcessRoadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereRealRoad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereShipmentDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereUpdatedAt($value)
 * @method static Builder|OrderInProcessRoad withTrashed()
 * @method static Builder|OrderInProcessRoad withoutTrashed()
 * @mixin Eloquent
 * @property-read OrderProcess|null $process
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class OrderInProcessRoad extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'order_in_process_roads';
    /**
     * @var string
     */
    protected $primaryKey = 'order_in_process_road_id';
    /**
     * @var string[]
     */
    protected $fillable = ['shipment_driver_id', 'route', 'distance', 'duration', 'selected', 'real_road', 'cord_updated', 'speed'];
    /**
     * @var string[]
     */
    protected $casts = ['route' => 'array', 'real_road' => 'array', 'distance' => 'float', 'duration' => 'integer'];
    /**
     * @var string[]
     */
    protected $attributes = ['real_road' => '{}', 'route' => '{}'];
    /**
     * @var string[]
     */
    protected $dates = ['cord_updated'];
    /**
     * @var string
     */
    protected string $map = 'inProcessRoad';

    /**
     * @return BelongsToThrough
     */
    public function order(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Order::class,
            OrderShippedDriver::class,
            null,
            '',
            [Order::class => 'order_id', OrderShippedDriver::class => 'shipment_driver_id']
        );
    }

    /**
     * @return HasOneDeep
     */
    public function order_initial(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->shipment_driver(), (new OrderShippedDriver())->order(), (new Order())->initial_data());
    }

    /**
     * @return BelongsTo
     */
    public function shipment_driver(): BelongsTo
    {
        return $this->belongsTo(OrderShippedDriver::class, 'shipment_driver_id', 'order_shipped_driver_id');
    }

    /**
     * @return HasOneDeep
     */
    public function order_initial_tariff(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations(
            $this->shipment_driver(),
            (new OrderShippedDriver())->order(),
            (new Order())->initial_data(),
            (new OrderInitialData())->initial_tariff()
        );
    }

    /**
     * @return HasOneDeep
     */
    public function order_second_tariff(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations(
            $this->shipment_driver(),
            (new OrderShippedDriver())->order(),
            (new Order())->initial_data(),
            (new OrderInitialData())->second_tariff()
        );
    }

    /**
     * @param $value
     * @return array|mixed
     * @throws JsonException
     */
    public function getRouteAttribute($value)
    {
        return $value ? json_decode($value, true, 512, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE) : [];
    }

    /**
     * @param $value
     * @return array|mixed
     * @throws JsonException
     */
    public function getRealRoadAttribute($value)
    {
        return $value ? json_decode($value, true, 512, JSON_THROW_ON_ERROR) : [];
    }
}
