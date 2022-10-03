<?php

declare(strict_types=1);


namespace Src\Models\Order;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ServiceEntity\Models\ServiceModel;

/**
 * Class OrderFeedbackOption
 *
 * @package Src\Models\Order
 * @property int|null $order_feedback_option_id
 * @property int|null $option
 * @property string|null $name
 * @property int|null $completed
 * @property int|null $canceled
 * @property int|null $reseted
 * @property string|null $owner_type
 * @property-read Collection|\Src\Models\Order\OrderFeedback[] $feedback
 * @property-read int|null $feedback_count
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereCanceled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereOrderFeedbackOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereOwnerType($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereReseted($value)
 * @property mixed $assessment
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel within($geometryColumn, $polygon)
 */
class OrderFeedbackOption extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'order_feedback_options';

    /**
     * @var string
     */
    protected $primaryKey = 'order_feedback_option_id';

    /**
     * @var array
     */
    protected $fillable = ['option', 'name', 'completed', 'canceled', 'owner_type', 'assessment'];

    /**
     * @return HasMany
     */
    public function feedback(): HasMany
    {
        return $this->hasMany(OrderFeedback::class);
    }
}
