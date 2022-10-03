<?php

declare(strict_types=1);

namespace Src\Models\Franchise;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Location\City;
use Src\Models\Location\Region;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * Class FranchiseRegion
 *
 * @package Src\Models
 * @property int $franchise_region_id
 * @property int|null $franchise_id
 * @property int|null $region_id
 * @property-read Collection|Region[] $regions
 * @property-read int|null $regions_count
 * @method static Builder|FranchiseRegion newModelQuery()
 * @method static Builder|FranchiseRegion newQuery()
 * @method static Builder|FranchiseRegion query()
 * @method static Builder|FranchiseRegion whereFranchiseId($value)
 * @method static Builder|FranchiseRegion whereFranchiseRegionId($value)
 * @method static Builder|FranchiseRegion whereRegionId($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property false|string $city
 * @property-read Region|null $region
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|FranchiseRegion whereCity($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|\Src\Models\Franchise\FranchiseCity[] $franchise_cities
 * @property-read int|null $franchise_cities_count
 * @property-read Collection|City[] $cities
 * @property-read int|null $cities_count
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
 * @property string $created_at
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|FranchiseRegion whereCreatedAt($value)
 */
class FranchiseRegion extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'franchise_region';
    /**
     * @var string
     */
    protected $primaryKey = 'franchise_region_id';
    /**
     * @var array
     */
    protected $fillable = ['region_id', 'franchise_id'];

    /**
     * @return BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    /**
     * @return HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'region_id', 'region_id');
    }

    /**
     * @return HasMany
     */
    public function franchise_cities(): HasMany
    {
        return $this->hasMany(FranchiseCity::class, 'franchise_region_id', 'franchise_region_id');
    }
}
