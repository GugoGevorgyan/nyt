<?php

declare(strict_types=1);

namespace Src\Models\Tariff;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Location\Area;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class TariffRegionBehind
 *
 * @package Src\Models\Tariff
 * @property int $tariff_behind_mkad_id
 * @property int $tariff_id
 * @property int $tariff_type_id
 * @property int|null $price_distance_1_15
 * @property int|null $price_distance_16_30
 * @property int|null $price_distance_31_60
 * @property int|null $price_distance_61_more
 * @property int|null $price_distance_1_15_minute
 * @property int|null $price_distance_16_30_minute
 * @property int|null $price_distance_31_60_minute
 * @property int|null $price_distance_61_more_minute
 * @property int|null $back
 * @property int $back_minute
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TariffPriceType $tariffType
 * @property-read Tariff $tariffs
 * @method static Builder|TariffRegionBehind newModelQuery()
 * @method static Builder|TariffRegionBehind newQuery()
 * @method static Builder|TariffRegionBehind query()
 * @method static Builder|TariffRegionBehind whereBack($value)
 * @method static Builder|TariffRegionBehind whereBackMinute($value)
 * @method static Builder|TariffRegionBehind whereCreatedAt($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance115($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance115Minute($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance1630($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance1630Minute($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance3160($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance3160Minute($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance61More($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance61MoreMinute($value)
 * @method static Builder|TariffRegionBehind whereStatus($value)
 * @method static Builder|TariffRegionBehind whereTariffBehindMkadId($value)
 * @method static Builder|TariffRegionBehind whereTariffId($value)
 * @method static Builder|TariffRegionBehind whereTariffTypeId($value)
 * @method static Builder|TariffRegionBehind whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property float $zone_distance
 * @property float $price_km
 * @property float $price_min
 * @property int|null $free_wait_stop_minutes
 * @property float|null $paid_wait_stop_minute
 * @property int|null $enable_speed_wait
 * @property int|null $speed_wait_limit
 * @property float|null $speed_wait_price_minute
 * @property-read Tariff $tariff
 * @property-read Tariff|null $to_tariff
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|TariffRegionBehind whereEnableSpeedWait($value)
 * @method static Builder|TariffRegionBehind whereFreeWaitStopMinutes($value)
 * @method static Builder|TariffRegionBehind wherePaidWaitStopMinute($value)
 * @method static Builder|TariffRegionBehind wherePriceKm($value)
 * @method static Builder|TariffRegionBehind wherePriceMin($value)
 * @method static Builder|TariffRegionBehind whereSpeedWaitLimit($value)
 * @method static Builder|TariffRegionBehind whereSpeedWaitPriceMinute($value)
 * @method static Builder|TariffRegionBehind whereZoneDistance($value)
 * @property int|null $sitting_fee
 * @property float|null $sit_price_km
 * @property float|null $sit_price_minute
 * @method static Builder|TariffRegionBehind whereSitPriceKm($value)
 * @method static Builder|TariffRegionBehind whereSitPriceMinute($value)
 * @method static Builder|TariffRegionBehind whereSittingFee($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $change_initial_price_percent
 * @property int $merge_km_minute
 * @method static Builder|TariffRegionBehind whereChangeInitialPricePercent($value)
 * @method static Builder|TariffRegionBehind whereMergeKmMinute($value)
 * @property int $tariff_region_behind_id
 * @property int|null $tariff_region_id
 * @property int $price_type_id
 * @property int $sit_type_id
 * @property string|null $sit_fix_price
 * @property int $minimal_distance_value KM
 * @property int $minimal_duration_value MINUTE
 * @property-read TariffRegionCity|null $tariff_region
 * @property-read TariffPriceType $tariff_type
 * @property string $cancel_fee
 * @property-read Collection|TariffRent[] $rent_behind
 * @property-read int|null $rent_behind_count
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|TariffRegionBehind whereCancelFee($value)
 * @method static Builder|TariffRegionBehind whereMinimalDistanceValue($value)
 * @method static Builder|TariffRegionBehind whereMinimalDurationValue($value)
 * @method static Builder|TariffRegionBehind wherePriceTypeId($value)
 * @method static Builder|TariffRegionBehind whereSitFixPrice($value)
 * @method static Builder|TariffRegionBehind whereSitTypeId($value)
 * @method static Builder|TariffRegionBehind whereTariffRegionBehindId($value)
 * @method static Builder|TariffRegionBehind whereTariffRegionId($value)
 * @property-read Collection|\Src\Models\Tariff\TariffRent[] $tariff_rents
 * @property-read int|null $tariff_rents_count
 * @property-read \Src\Models\Tariff\TariffRentAlt|null $rent_alt
 */
class TariffRegionBehind extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'tariff_region_behind';
    /**
     * @var string
     */
    protected $primaryKey = 'tariff_region_behind_id';
    /**
     * @var array
     */
    protected $casts = ['price_km' => 'float', 'price_min' => 'float', 'paid_wait_stop_minute' => 'float', 'zone_distance' => 'float'];
    /**
     * @var array
     */
    protected $fillable = [
        'tariff_region_id',
        'tariff_id',
        'zone_distance',
        'price_km',
        'price_min',
        'sitting_fee',
        'cancel_fee',
        'sit_fix_price',
        'sit_price_km',
        'sit_price_minute',
        'free_wait_stop_minutes',
        'paid_wait_stop_minute',
        'enable_speed_wait',
        'speed_wait_limit',
        'enable_speed_wait',
        'speed_wait_price_minute',
        'change_initial_price_percent',
        'minimal_distance_value',
        'minimal_duration_value',
        'merge_km_minute',
        'sit_type_id',
        'price_type_id',
    ];
    /**
     * @var string
     */
    protected string $map = 'tariffRegionBehind';

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
    public function tariff_region(): BelongsTo
    {
        return $this->belongsTo(TariffRegionCity::class, 'tariff_region_id', 'tariff_region_city_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function area(): BelongsToThrough
    {
        return $this->belongsToThrough(Area::class, TariffRegionCity::class, null, '', [TariffRegionCity::class => 'tariff_region_id']);
    }

    /**
     * @return MorphOne
     */
    public function to_tariff(): MorphOne
    {
        return $this->morphOne(Tariff::class, 'current_tariff');
    }

    /**
     * @return BelongsTo
     */
    public function tariff_type(): BelongsTo
    {
        return $this->belongsTo(TariffPriceType::class, 'tariff_type_id', 'sit_type_id');
    }

    /**
     * @return MorphToMany
     */
    public function tariff_rents(): MorphToMany
    {
        return $this->morphToMany(
            TariffRent::class,
            'alt',
            'tariff_rent_alt',
            'rent_id',
            'alt_id',
            'tariff_rent_id',
            'tariff_region_behind_id',
        );
    }

    /**
     * @return MorphOne
     */
    public function rent_alt(): MorphOne
    {
        return $this->morphOne(TariffRentAlt::class, 'rent_alt', 'alt_type', 'alt_id');
    }
}

