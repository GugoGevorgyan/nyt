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
 * Class AddressDetail
 *
 * @package Src\Models\Monitor
 * @property int $address_detail_id
 * @property int $initial_address_id
 * @property int $end_address_id
 * @property int|null $distance
 * @property int|null $duration
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Address $end_address
 * @property-read Address $initial_address
 * @property-read Collection|AddressesRoute[] $routes
 * @property-read int|null $routes_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|AddressDetail newModelQuery()
 * @method static Builder|AddressDetail newQuery()
 * @method static Builder|AddressDetail query()
 * @method static Builder|AddressDetail whereAddressDetailId($value)
 * @method static Builder|AddressDetail whereCreatedAt($value)
 * @method static Builder|AddressDetail whereDistance($value)
 * @method static Builder|AddressDetail whereDuration($value)
 * @method static Builder|AddressDetail whereEndAddressId($value)
 * @method static Builder|AddressDetail whereInitialAddressId($value)
 * @method static Builder|AddressDetail whereUpdatedAt($value)
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
class AddressDetail extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'addresses_details';
    /**
     * @var string
     */
    protected $primaryKey = 'address_detail_id';
    /**
     * @var string[]
     */
    protected $fillable = ['initial_address_id', 'end_address_id', 'distance', 'duration'];
    /**
     * @var string[]
     */
    protected $casts = ['created_at' => 'timestamp'];

    /**
     * @return BelongsTo
     */
    public function initial_address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'initial_address_id', 'address_id');
    }

    /**
     * @return BelongsTo
     */
    public function end_address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'end_address_id', 'address_id');
    }

    /**
     * @return HasMany
     */
    public function routes(): HasMany
    {
        return $this->hasMany(AddressesRoute::class, 'detail_id', 'address_detail_id');
    }
}
