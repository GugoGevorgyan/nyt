<?php

declare(strict_types=1);

namespace Src\Models\Monitor;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class Address
 *
 * @package Src\Models\Monitor
 * @property int $address_id
 * @property string $address
 * @property mixed $coordinate
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|AddressDetail[] $end_route
 * @property-read int|null $end_route_count
 * @property-read Collection|AddressesRoute[] $end_routes
 * @property-read int|null $end_routes_count
 * @property-read Collection|AddressDetail[] $initial_route
 * @property-read int|null $initial_route_count
 * @property-read Collection|AddressesRoute[] $initial_routes
 * @property-read int|null $initial_routes_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|Address newModelQuery()
 * @method static Builder|Address newQuery()
 * @method static Builder|Address query()
 * @method static Builder|Address whereAddress($value)
 * @method static Builder|Address whereAddressId($value)
 * @method static Builder|Address whereCoordinate($value)
 * @method static Builder|Address whereCreatedAt($value)
 * @method static Builder|Address whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $cord_text
 * @method static Builder|Address whereCordText($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property string|null $locality
 * @property string|null $province
 * @property float|null $lat
 * @property float|null $lut
 * @property string|null $code
 * @method static Builder|Address whereCode($value)
 * @method static Builder|Address whereLat($value)
 * @method static Builder|Address whereLocality($value)
 * @method static Builder|Address whereLut($value)
 * @method static Builder|Address whereProvince($value)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string|null $short_address
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
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|Address whereShortAddress($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel within($geometryColumn, $polygon)
 */
class Address extends ServiceModel
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
    protected $table = 'addresses';
    /**
     * @var string
     */
    protected $primaryKey = 'address_id';
    /**
     * @var string[]
     */
    protected $fillable = ['address', 'short_address', 'lat', 'lut', 'province', 'locality', 'code'];
    /**
     * @var string[]
     */
    protected $casts = ['lat' => 'float', 'lut' => 'float', 'created_at' => 'timestamp'];

    /**
     * @return HasMany
     */
    public function initial_route(): HasMany
    {
        return $this->hasMany(AddressDetail::class, 'initial_address_id', 'address_id');
    }

    /**
     * @return HasMany
     */
    public function end_route(): HasMany
    {
        return $this->hasMany(AddressDetail::class, 'end_address_id', 'address_id');
    }

    /**
     * @return HasOneThrough
     */
    public function initial_routes(): HasOneThrough
    {
        return $this->hasOneThrough(AddressesRoute::class, AddressDetail::class, 'initial_address_id', 'detail_id');
    }

    /**
     * @return HasOneThrough
     */
    public function end_routes(): HasOneThrough
    {
        return $this->hasOneThrough(AddressesRoute::class, AddressDetail::class, 'end_address_id', 'detail_id');
    }
}
