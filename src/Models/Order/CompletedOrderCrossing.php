<?php

declare(strict_types=1);

namespace Src\Models\Order;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ServiceEntity\Models\ServiceModel;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Src\Models\Order\CompletedOrderCrossing
 *
 * @property int $completed_order_crossing_id
 * @property int $completed_id
 * @property string|null $in_price
 * @property string|null $out_price
 * @property string|null $in_distance_price
 * @property string|null $in_duration_price
 * @property string|null $out_distance_price
 * @property string|null $out_duration_price
 * @property array|null $in_trajectory
 * @property array|null $out_trajectory
 * @property float|null $in_distance
 * @property float|null $out_distance
 * @property int|null $in_duration
 * @property int|null $out_duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Src\Models\Order\CompletedOrder $completed
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereCompletedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereCompletedOrderCrossingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereInDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereInDistancePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereInDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereInDurationPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereInPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereInTrajectory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereOutDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereOutDistancePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereOutDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereOutDurationPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereOutPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereOutTrajectory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompletedOrderCrossing extends ServiceModel
{


    /**
     * @var string
     */
    protected $table = 'completed_orders_crossing';
    /**
     * @var string
     */
    protected $primaryKey = 'completed_order_crossing_id';
    /**
     * @var string[]
     */
    protected $casts = ['in_trajectory' => 'array', 'out_trajectory' => 'array'];
    /**
     * @var string[]
     */
    protected $fillable = [
        'completed_id',
        'in_price',
        'out_price',
        'in_distance_price',
        'out_distance_price',
        'in_duration_price',
        'out_duration_price',
        'in_distance',
        'out_distance',
        'in_duration',
        'out_duration',
        'in_trajectory',
        'out_trajectory',
    ];

    /**
     * @return BelongsTo
     */
    public function completed(): BelongsTo
    {
        return $this->belongsTo(CompletedOrder::class, 'completed_id', 'completed_order_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function order(): BelongsToThrough
    {
        return $this->belongsToThrough(Order::class, CompletedOrder::class, null, '', [Order::class => 'order_id', CompletedOrder::class => 'order_id']);
    }
}
