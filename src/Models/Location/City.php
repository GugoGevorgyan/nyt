<?php

declare(strict_types=1);

namespace Src\Models\Location;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Franchise\Franchise;
use Src\Models\Tariff\Tariff;
use Src\Models\TransportStations\Airport;
use Src\Models\TransportStations\Metro;
use Src\Models\TransportStations\RailwayStation;
use Staudenmeir\EloquentJsonRelations\Relations\HasManyJson;

/**
 * Class City
 *
 * @package Src\Models\Location
 * @property int $city_id
 * @property int $region_id
 * @property int $country_id
 * @property string $name
 * @property-read Country $country
 * @property-read Region $region
 * @method static Builder|City newModelQuery()
 * @method static Builder|City newQuery()
 * @method static Builder|City query()
 * @method static Builder|City whereCityId($value)
 * @method static Builder|City whereCountryId($value)
 * @method static Builder|City whereName($value)
 * @method static Builder|City whereRegionId($value)
 * @mixin Eloquent
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Tariff[] $tariffs
 * @property-read int|null $tariffs_count
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|City whereCreatedAt($value)
 * @method static Builder|City whereDeletedAt($value)
 * @method static Builder|City whereUpdatedAt($value)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|Franchise[] $franchisee
 * @property-read int|null $franchisee_count
 * @property-read Collection|Airport[] $airports
 * @property-read int|null $airports_count
 * @property-read Collection|Metro[] $metros
 * @property-read int|null $metros_count
 * @property-read Collection|RailwayStation[] $railways
 * @property-read int|null $railways_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class City extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'cities';
    /**
     * @var string
     */
    protected $primaryKey = 'city_id';
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }

    /**
     * @return BelongsToMany
     */
    public function franchisee(): BelongsToMany
    {
        return $this->belongsToMany(Franchise::class, 'franchise_city', $this->primaryKey, 'franchise_id');
    }

    /**
     * @return HasManyJson
     */
    public function tariffs(): HasManyJson
    {
        return $this->hasManyJson(Tariff::class, 'city->ids');
    }

    /**
     * @return HasMany
     */
    public function metros(): HasMany
    {
        return $this->hasMany(Metro::class,$this->primaryKey);
    }

    /**
     * @return HasMany
     */
    public function airports(): HasMany
    {
        return $this->hasMany(Airport::class,$this->primaryKey);
    }

    /**
     * @return HasMany
     */
    public function railways(): HasMany
    {
        return $this->hasMany(RailwayStation::class,$this->primaryKey);
    }
}
