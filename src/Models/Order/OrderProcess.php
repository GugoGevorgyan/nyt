<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class OrderProcess
 *
 * @package Src\Models\Order
 * @property int $order_process_id
 * @property int $processed_id
 * @property string $processed_type
 * @property float|null $price
 * @property float|null $sitting_price
 * @property float|null $waiting_price
 * @property int|null $travel_time
 * @property int|null $pause_time
 * @property int|null $distance_traveled
 * @property int|null $waiting_time
 * @property Carbon|null $cord_updated
 * @property int|null $speed
 * @property Carbon|null $price_passed
 * @property-read Model|Eloquent $processed
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|OrderProcess newModelQuery()
 * @method static Builder|OrderProcess newQuery()
 * @method static Builder|OrderProcess query()
 * @method static Builder|OrderProcess whereCordUpdated($value)
 * @method static Builder|OrderProcess whereDistanceTraveled($value)
 * @method static Builder|OrderProcess whereOrderProcessId($value)
 * @method static Builder|OrderProcess wherePrice($value)
 * @method static Builder|OrderProcess wherePricePassed($value)
 * @method static Builder|OrderProcess whereProcessedId($value)
 * @method static Builder|OrderProcess whereProcessedType($value)
 * @method static Builder|OrderProcess whereSittingPrice($value)
 * @method static Builder|OrderProcess whereSpeed($value)
 * @method static Builder|OrderProcess whereTravelTime($value)
 * @method static Builder|OrderProcess whereWaitingTime($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $order_shipped_id
 * @property float|null $calculate_price
 * @property float|null $pause_price
 * @property-read OrderShippedDriver $shipped
 * @method static Builder|OrderProcess whereCalculatePrice($value)
 * @method static Builder|OrderProcess whereOrderShippedId($value)
 * @method static Builder|OrderProcess wherePausePrice($value)
 * @property string|null $total_price
 * @method static Builder|OrderProcess whereTotalPrice($value)
 * @property string|null $increment_price
 * @property string|null $options_price
 * @property string|null $cancel_price
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|OrderProcess whereCancelPrice($value)
 * @method static Builder|OrderProcess whereIncrementPrice($value)
 * @method static Builder|OrderProcess whereOptionsPrice($value)
 * @method static Builder|OrderProcess whereWaitingPrice($value)
 * @method static Builder|OrderProcess wherePauseTime($value)
 */
class OrderProcess extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'order_processes';
    /**
     * @var string
     */
    protected $primaryKey = 'order_process_id';
    /**
     * @var array
     */
    protected $fillable = [
        'order_shipped_id',
        'travel_time',
        'waiting_time',
        'pause_time',
        'distance_traveled',
        'speed',
        'price',
        'options_price',
        'calculate_price',
        'increment_price',
        'total_price',
        'pause_price',
        'sitting_price',
        'cancel_price',
        'waiting_price',
        'cord_updated',
        'price_passed',
    ];
    /**
     * @var string[]
     */
    protected $dates = ['cord_updated', 'price_passed'];
    /**
     * @var string
     */
    protected string $map = 'orderProcess';

    /**
     * @var string[]
     */
    protected $casts = ['cancel_price' => 'float'];
    /**
     * @return BelongsTo
     */
    public function shipped(): BelongsTo
    {
        return $this->belongsTo(OrderShippedDriver::class, 'order_shipped_id', 'order_shipped_driver_id');
    }

    /**
     * @return BelongsToThrough //@todo non tested
     */
    public function order(): BelongsToThrough
    {
        return $this->belongsToThrough(Order::class, OrderShippedDriver::class, '', null,
            [Order::class => 'order_id', OrderShippedDriver::class => 'order_shipped_id']);
    }
}
