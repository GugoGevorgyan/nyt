<?php

declare(strict_types=1);

namespace Src\Models\Driver;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ServiceEntity\Models\ServiceModel;

/**
 * Class DriverAddress
 *
 * @package Src\Models\Driver
 * @property int $driver_address_id
 * @property int|null $driver_id
 * @property string|null $target
 * @property string|null $address
 * @property mixed|null $coordinate
 * @property int|null $active
 * @property string|null $created_at
 * @property-read Driver|null $driver
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|DriverAddress newModelQuery()
 * @method static Builder|DriverAddress newQuery()
 * @method static Builder|DriverAddress query()
 * @method static Builder|DriverAddress whereActive($value)
 * @method static Builder|DriverAddress whereAddress($value)
 * @method static Builder|DriverAddress whereCoordinate($value)
 * @method static Builder|DriverAddress whereCreatedAt($value)
 * @method static Builder|DriverAddress whereDriverAddressId($value)
 * @method static Builder|DriverAddress whereDriverId($value)
 * @method static Builder|DriverAddress whereTarget($value)
 * @mixin Eloquent
 * @property array|null $road
 * @method static Builder|DriverAddress whereRoad($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $target_duration
 * @property int|null $target_distance
 * @property array|null $target_road
 * @method static Builder|DriverAddress whereTargetDistance($value)
 * @method static Builder|DriverAddress whereTargetDuration($value)
 * @method static Builder|DriverAddress whereTargetRoad($value)
 * @property float $lat
 * @property float $lut
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
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|DriverAddress whereLat($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|DriverAddress whereLut($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel within($geometryColumn, $polygon)
 */
class DriverAddress extends ServiceModel
{
    protected const LATITUDE = 'lat';
    protected const LONGITUDE = 'lut';
    protected static bool $kilometers = false;

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'driver_addresses';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_address_id';
    /**
     * @var array
     */
    protected $fillable = ['target', 'address', 'driver_id', 'lat', 'lut', 'target_road', 'target_distance', 'target_duration', 'active'];
    /**
     * @var string[]
     */
    protected $casts = ['lat' => 'float', 'lut' => 'float', 'target_road' => 'array', 'active' => 'bool'];

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
