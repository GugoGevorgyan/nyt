<?php

declare(strict_types=1);

namespace Src\Models\Tariff;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Location\Area;

/**
 * Class TariffRent
 *
 * @package Src\Models\Tariff
 * @property int $tariff_rent_id
 * @property int $tariff_id
 * @property int|null $area_id
 * @property int|null $cancel_fee
 * @property float $zone_distance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tariff $tariff
 * @mixin Eloquent
 * @property int|null $price_type_id
 * @property int|null $sitting_fee
 * @property string|null $sit_fix_price
 * @property string|null $sit_price_km
 * @property string|null $sit_price_minute
 * @property int $hours
 * @property-read Area|null $area
 * @property-read Collection|TariffRegionBehind[] $behind
 * @property-read int|null $behind_count
 * @property-read Collection|TariffDestination[] $destination
 * @property-read int|null $destination_count
 * @property-read Collection|TariffRegionCity[] $region
 * @property-read int|null $region_count
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|TariffRent newModelQuery()
 * @method static Builder|TariffRent newQuery()
 * @method static Builder|TariffRent query()
 * @method static Builder|TariffRent whereAreaId($value)
 * @method static Builder|TariffRent whereCancelFee($value)
 * @method static Builder|TariffRent whereCreatedAt($value)
 * @method static Builder|TariffRent whereHours($value)
 * @method static Builder|TariffRent wherePriceTypeId($value)
 * @method static Builder|TariffRent whereSitFixPrice($value)
 * @method static Builder|TariffRent whereSitPriceKm($value)
 * @method static Builder|TariffRent whereSitPriceMinute($value)
 * @method static Builder|TariffRent whereSittingFee($value)
 * @method static Builder|TariffRent whereTariffId($value)
 * @method static Builder|TariffRent whereTariffRentId($value)
 * @method static Builder|TariffRent whereUpdatedAt($value)
 * @method static Builder|TariffRent whereZoneDistance($value)
 * @property-read Collection|\Src\Models\Tariff\TariffRegionBehind[] $alt_behind
 * @property-read int|null $alt_behind_count
 * @property-read Collection|\Src\Models\Tariff\TariffDestination[] $alt_destination
 * @property-read int|null $alt_destination_count
 * @property-read Collection|\Src\Models\Tariff\TariffRegionCity[] $alt_region
 * @property-read int|null $alt_region_count
 * @property-read Collection|\Src\Models\Tariff\TariffRentAlt[] $rent_alts
 * @property-read int|null $rent_alts_count
 * @property-read \Src\Models\Tariff\Tariff|null $to_tariff
 */
class TariffRent extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'tariff_rents';
    /**
     * @var string
     */
    protected $primaryKey = 'tariff_rent_id';
    /**
     * @var array
     */
    protected $casts = ['zone_distance' => 'float'];
    /**
     * @var array
     */
    protected $fillable = [
        'tariff_id',
        'area_id',
        'cancel_fee',
        'sitting_fee',
        'sit_fix_price',
        'sit_price_km',
        'sit_price_minute',
        'hours',
    ];
    /**
     * @var string
     */
    protected string $map = 'tariffRent';

    /**
     * @return BelongsTo
     */
    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class, 'tariff_id', 'tariff_id');
    }

    /**
     * @return BelongsTo
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id', 'area_id');
    }

    /**
     * @return HasMany
     */
    public function rent_alts(): HasMany
    {
        return $this->hasMany(TariffRentAlt::class, 'rent_id', 'tariff_rent_id');
    }

    /**
     * @return MorphToMany
     */
    public function alt_behind(): MorphToMany
    {
        return $this->morphedByMany(
            TariffRegionBehind::class,
            'alt',
            'tariff_rent_alt',
            'rent_id',
            'alt_id',
            'tariff_rent_id',
            'tariff_region_behind_id',
        )->withPivot('in_area','tariff_rent_alt_id');
    }

    /**
     * @return MorphToMany
     */
    public function alt_region(): MorphToMany
    {
        return $this->morphedByMany(
            TariffRegionCity::class,
            'alt',
            'tariff_rent_alt',
            'rent_id',
            'alt_id',
            'tariff_rent_id',
            'tariff_region_city_id'
        )->withPivot('in_area','tariff_rent_alt_id');
    }

    /**
     * @return MorphToMany
     */
    public function alt_destination(): MorphToMany
    {
        return $this->morphedByMany(
            TariffDestination::class,
            'alt',
            'tariff_rent_alt',
            'rent_id',
            'alt_id',
            'tariff_rent_id',
            'tariff_destination_id',
        )->withPivot('in_area','tariff_rent_alt_id');
    }

    /**
     * @return MorphOne
     */
    public function to_tariff(): MorphOne
    {
        return $this->morphOne(Tariff::class, 'current_tariff');
    }
}
