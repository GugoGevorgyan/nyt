<?php

declare(strict_types=1);

namespace Src\Models\Order;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ServiceEntity\Models\ServiceModel;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class CompletedOrderChange
 *
 * @package Src\Models\Order
 * @property int $completed_order_change_id
 * @property int $completed_id
 * @property int $changer_id system_workers
 * @property string $old_price
 * @property string $new_price
 * @property float|null $old_distance
 * @property float|null $new_distance
 * @property int|null $old_duration
 * @property int|null $new_duration
 * @property mixed|null $old_trajectory
 * @property mixed|null $new_trajectory
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Src\Models\Order\CompletedOrder $completed
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereChangerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereCompletedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereCompletedOrderChangeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereNewDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereNewDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereNewPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereNewTrajectory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereOldDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereOldDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereOldPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereOldTrajectory($value)
 * @mixin \Eloquent
 */
class CompletedOrderChange extends ServiceModel
{


    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'completed_order_changes';
    /**
     * @var string
     */
    protected $primaryKey = 'completed_order_change_id';
    /**
     * @var array
     */
    protected $casts = ['old_distance' => 'float', 'new_distance' => 'float'];
    /**
     * @var array
     */
    protected $attributes = ['new_trajectory' => '{}', 'old_trajectory' => '{}'];
    /**
     * @var array
     */
    protected $dates = ['created_at'];
    /**
     * @var array
     */
    protected $fillable = [
        'completed_id',
        'changer_id',
        'old_price',
        'new_price',
        'old_distance',
        'new_distance',
        'old_duration',
        'new_duration',
        'old_trajectory',
        'new_trajectory'
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
        return $this->belongsToThrough(Order::class, CompletedOrder::class, null, '',
            [Order::class => 'order_id', CompletedOrder::class => 'completed_id']);
    }
}
