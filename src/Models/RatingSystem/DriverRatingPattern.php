<?php

declare(strict_types=1);

namespace Src\Models\RatingSystem;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class DriverRatingPattern
 *
 * @package Src\Models\RatingSystem
 * @property int $driver_rating_pattern_id
 * @property int $type
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property float $value
 * @property string $symbol
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|DriverRatingPattern newModelQuery()
 * @method static Builder|DriverRatingPattern newQuery()
 * @method static Builder|DriverRatingPattern query()
 * @method static Builder|DriverRatingPattern whereAlias($value)
 * @method static Builder|DriverRatingPattern whereCreatedAt($value)
 * @method static Builder|DriverRatingPattern whereDescription($value)
 * @method static Builder|DriverRatingPattern whereDriverRatingPatternId($value)
 * @method static Builder|DriverRatingPattern whereName($value)
 * @method static Builder|DriverRatingPattern whereSymbol($value)
 * @method static Builder|DriverRatingPattern whereType($value)
 * @method static Builder|DriverRatingPattern whereUpdatedAt($value)
 * @method static Builder|DriverRatingPattern whereValue($value)
 * @mixin Eloquent
 * @property string $inc_dec
 * @property-read Collection|DriverRating[] $rejected_order_rating
 * @property-read int|null $rejected_order_rating_count
 * @method static Builder|DriverRatingPattern whereIncDec($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
class DriverRatingPattern extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'driver_rating_patterns';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_rating_pattern_id';
    /**
     * @var array
     */
    protected $fillable = ['name', 'alias', 'description', 'symbol', 'value', 'type'];
}
