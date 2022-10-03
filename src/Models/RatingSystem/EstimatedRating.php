<?php

declare(strict_types=1);

namespace Src\Models\RatingSystem;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Driver\Driver;
use Src\Models\Order\Order;
use Src\Models\Order\OrderShippedDriver;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * Src\Models\RatingSystem\EstimatedRating
 *
 * @property int $estimated_rating_id
 * @property int $order_id
 * @property int $driver_id
 * @property float $added_rating
 * @property float $remove_rating
 * @property array|null $added_patterns
 * @property array|null $remove_patterns
 * @property mixed|null $outcome
 * @property Carbon $created_at
 * @property-read Driver $driver
 * @property-read Order $order
 * @property-read Collection|OrderShippedDriver[] $ordering_shipments_driver
 * @property-read int|null $ordering_shipments_driver_count
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|EstimatedRating newModelQuery()
 * @method static Builder|EstimatedRating newQuery()
 * @method static Builder|EstimatedRating query()
 * @method static Builder|EstimatedRating whereAddedPatterns($value)
 * @method static Builder|EstimatedRating whereAddedRating($value)
 * @method static Builder|EstimatedRating whereCreatedAt($value)
 * @method static Builder|EstimatedRating whereDriverId($value)
 * @method static Builder|EstimatedRating whereEstimatedRatingId($value)
 * @method static Builder|EstimatedRating whereOrderId($value)
 * @method static Builder|EstimatedRating whereOutcome($value)
 * @method static Builder|EstimatedRating whereRemovePatterns($value)
 * @method static Builder|EstimatedRating whereRemoveRating($value)
 * @mixin Eloquent
 */
class EstimatedRating extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'estimated_ratings';
    /**
     * @var string
     */
    protected $primaryKey = 'estimated_rating_id';
    /**
     * @var string[]
     */
    protected $fillable = ['order_id', 'driver_id', 'added_rating', 'remove_rating', 'added_patterns', 'remove_patterns', 'outcome'];
    /**
     * @var string[]
     */
    protected $casts = ['added_patterns' => 'json', 'remove_patterns' => 'json'];
    /**
     * @var string[]
     */
    protected $attributes = ['added_patterns' => '{"ids": []}', 'remove_patterns' => '{"ids": []}',];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * @return HasMany
     */
    public function ordering_shipments_driver(): HasMany
    {
        return $this->hasMany(OrderShippedDriver::class, $this->primaryKey);
    }

    /**
     * @return BelongsToJson
     */
    public function added_pattern(): BelongsToJson
    {
        return $this->belongsToJson(DriverRatingPattern::class, 'added_patterns->ids');
    }

    /**
     * @return BelongsToJson
     */
    public function remove_pattern(): BelongsToJson
    {
        return $this->belongsToJson(DriverRatingPattern::class, 'remove_patterns->ids');
    }
}
