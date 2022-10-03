<?php

declare(strict_types=1);

namespace Src\Models\Location;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Franchise\Franchise;
use Src\Models\Franchise\FranchiseRegion;
use Src\Models\Tariff\Tariff;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * Class Region
 *
 * @package Src\Models
 * @property int $region_id
 * @property string $name
 * @property string|null $country_iso_2
 * @property int $country_id
 * @property-read Collection|City[] $cities
 * @property-read Country $country
 * @property-read Collection|Franchise[] $franchises
 * @method static Builder|Region newModelQuery()
 * @method static Builder|Region newQuery()
 * @method static Builder|Region query()
 * @method static Builder|Region whereCountryId($value)
 * @method static Builder|Region whereCountryIso2($value)
 * @method static Builder|Region whereName($value)
 * @method static Builder|Region whereRegionId($value)
 * @mixin Eloquent
 * @property string|null $iso_2
 * @property string|null $deleted_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read int|null $cities_count
 * @property-read int|null $franchises_count
 * @property-read Collection|Tariff[] $tariffs
 * @property-read int|null $tariffs_count
 * @method static Builder|Region whereCreatedAt($value)
 * @method static Builder|Region whereDeletedAt($value)
 * @method static Builder|Region whereIso2($value)
 * @method static Builder|Region whereUpdatedAt($value)
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property-read Collection|FranchiseRegion[] $FranchiseRegion
 * @property-read int|null $franchise_region_count
 * @property-read Collection|Franchise[] $franchisee
 * @property-read int|null $franchisee_count
 * @method static Builder|ServiceModel except($values = [])
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
 * @property array|null $cord
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|Region whereCord($value)
 */
class Region extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'regions';
    /**
     * @var string
     */
    protected $primaryKey = 'region_id';
    /**
     * @var array
     */
    protected $fillable = ['country_id', 'name', 'iso_2', 'cord'];
    /**
     * @var string[]
     */
    protected $casts = ['cord' => 'array'];
    /**
     * @var string[]
     */
    protected $attributes = ['cord' => '{}'];

    /**
     * @return belongsToMany
     */
    public function franchisee(): BelongsToMany
    {
        return $this->belongsToMany(Franchise::class, 'franchise_region', $this->primaryKey, 'franchise_id');
    }

    /**
     * @return HasMany
     */
    public function FranchiseRegion(): HasMany
    {
        return $this->hasMany(FranchiseRegion::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * @return HasMany
     */
    public function tariffs(): HasMany
    {
        return $this->hasManyJson(Tariff::class, 'region->ids');
    }

    /**
     * @return BelongsToJson
     */
    public function areas(): BelongsToJson
    {
        return $this->belongsToJson(Area::class, 'region->ids');
    }
}
