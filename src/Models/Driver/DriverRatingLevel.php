<?php

declare(strict_types=1);

namespace Src\Models\Driver;

use Illuminate\Database\Eloquent\Relations\HasMany;
use ServiceEntity\Models\ServiceModel;

/**
 * Class DriverRatingLevel
 *
 * @package Src\Models\Driver
 * @property int $driver_rating_level_id
 * @property string $level
 * @property int|null $from
 * @property int|null $before
 * @property-read \Illuminate\Database\Eloquent\Collection|\Src\Models\Driver\Driver[] $drivers
 * @property-read int|null $drivers_count
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel whereDriverRatingLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel whereLevel($value)
 * @mixin \Eloquent
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
class DriverRatingLevel extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'driver_rating_levels';

    /**
     * @var string
     */
    protected $primaryKey = 'driver_rating_level_id';

    /**
     * @var array
     */
    protected $fillable = ['level', 'from', 'before'];

    /**
     * @return HasMany
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'rating_level_id');
    }
}
