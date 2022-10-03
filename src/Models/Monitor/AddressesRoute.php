<?php

declare(strict_types=1);

namespace Src\Models\Monitor;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class AddressesRoute
 *
 * @package Src\Models\Monitor
 * @property int $address_route_id
 * @property int|null $detail_id
 * @property object|null $route
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AddressDetail|null $detail
 * @property-read Collection|AddressDetail[] $end_route
 * @property-read int|null $end_route_count
 * @property-read Collection|AddressDetail[] $initial_route
 * @property-read int|null $initial_route_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|AddressesRoute newModelQuery()
 * @method static Builder|AddressesRoute newQuery()
 * @method static Builder|AddressesRoute query()
 * @method static Builder|AddressesRoute whereAddressRouteId($value)
 * @method static Builder|AddressesRoute whereCreatedAt($value)
 * @method static Builder|AddressesRoute whereDetailId($value)
 * @method static Builder|AddressesRoute whereRoute($value)
 * @method static Builder|AddressesRoute whereUpdatedAt($value)
 * @mixin Eloquent
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
class AddressesRoute extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'addresses_routes';
    /**
     * @var string
     */
    protected $primaryKey = 'addresses_route_id';
    /**
     * @var string[]
     */
    protected $fillable = ['detail_id', 'route'];
    /**
     * @var string[]
     */
    protected $casts = ['route' => 'array', 'created_at' => 'timestamp'];

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
     * @return BelongsTo
     */
    public function detail(): BelongsTo
    {
        return $this->belongsTo(AddressDetail::class, 'detail_id', 'address_detail_id');
    }
}
