<?php

declare(strict_types=1);

namespace Src\Models\Location;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Tariff\TariffDestination;
use Src\Models\Tariff\TariffRegionCity;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * Class Area
 *
 * @package Src\Models
 * @property int $attribute_id
 * @property array|null $driver_license
 * @method static Builder|Area newModelQuery()
 * @method static Builder|Area newQuery()
 * @method static Builder|Area query()
 * @method static Builder|Area whereAttributeId($value)
 * @method static Builder|Area whereDriverLicense($value)
 * @mixin Eloquent
 * @property array|null $mkad_coordinates
 * @property array|null $ttk_coordinates
 * @property array|null $sad_coordinates
 * @method static Builder|Area whereMkadCoordinates($value)
 * @method static Builder|Area whereSadCoordinates($value)
 * @method static Builder|Area whereTtkCoordinates($value)
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property int $area_id
 * @property string $name
 * @property string|null $title
 * @property string|null $description
 * @property mixed $area
 * @property string|null $geo_area
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|TariffDestination[] $destination_tariff
 * @property-read int|null $destination_tariff_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Query\Builder|Area onlyTrashed()
 * @method static Builder|Area whereArea($value)
 * @method static Builder|Area whereAreaId($value)
 * @method static Builder|Area whereCreatedAt($value)
 * @method static Builder|Area whereDeletedAt($value)
 * @method static Builder|Area whereDescription($value)
 * @method static Builder|Area whereGeoArea($value)
 * @method static Builder|Area whereName($value)
 * @method static Builder|Area whereTitle($value)
 * @method static Builder|Area whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Area withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Area withoutTrashed()
 * @property string|null $cord
 * @method static Builder|Area whereCord($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $region_id
 * @method static Builder|Area whereRegionId($value)
 * @property array|null $region
 * @property int $type
 * @property-read Collection|TariffRegionCity[] $tariff_region_cities
 * @property-read int|null $tariff_region_cities_count
 */
class Area extends ServiceModel
{
    public const AREA_MKAD = 'MKAD_AREA';
    public const AREA_TTK = 'TTK_AREA';
    public const AREA_SAD = 'SAD_AREA';

    /**
     * @var string
     */
    protected $table = 'areas';
    /**
     * @var string
     */
    protected $primaryKey = 'area_id';
    /**
     * @var array
     */
    protected $fillable = ['name', 'geo_area', 'area', 'title', 'description'];
    /**
     * @var string[]
     */
    protected $casts = ['area' => 'array', 'region' => 'json'];
    /**
     * @var string[]
     */
    protected $attributes = ['region' => '{"ids": []}'];

    /**
     * @return HasOneDeep
     */
    public function tariff(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->tariff_region_cities(), (new TariffRegionCity())->tariff());
    }

    /**
     * @return HasMany
     */
    public function tariff_region_cities(): HasMany
    {
        return $this->hasMany(TariffRegionCity::class, $this->primaryKey);
    }

    /**
     * @return HasOneDeep
     */
    public function behind_tariff(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->tariff_region_cities(), (new TariffRegionCity())->behind());
    }

    /**
     * @return MorphToMany
     */
    public function destination_tariff(): MorphToMany
    {
        return $this->morphedByMany(TariffDestination::class, 'areable');
    }

    /**
     * @return BelongsToJson
     */
    public function regions(): BelongsToJson
    {
        return $this->belongsToJson(Region::class, 'region->ids');
    }
}
