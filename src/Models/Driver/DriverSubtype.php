<?php

namespace Src\Models\Driver;

use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Driver\DriverSubtype
 *
 * @property int $driver_subtype_id
 * @property int $driver_type_id
 * @property string $name
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype whereDriverSubtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype whereDriverTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype whereValue($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
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
class DriverSubtype extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'driver_subtypes';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_subtype_id';
    /**
     * @var string[]
     */
    protected $fillable = ['driver_type_id', 'name', 'value'];
}
