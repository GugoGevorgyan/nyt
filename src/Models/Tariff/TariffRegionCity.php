<?php

declare(strict_types=1);

namespace Src\Models\Tariff;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Location\Area;

/**
 * Class TariffRegionCity
 *
 * @package Src\Models\Tariff
 * @property int $tariff_behind_city_id
 * @property int $tariff_id
 * @property int|null $price
 * @property int|null $price_minute
 * @property int|null $minimal_price
 * @property int|null $intent
 * @property int|null $sit_price_km
 * @property int|null $sit_price_minute
 * @property int|null $free_wait_every_stop
 * @property int|null $paid_wait_every_stop
 * @property int|null $free_wait
 * @property int|null $paid_wait
 * @property int|null $back
 * @property int|null $back_minute
 * @property string|null $rounding_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tariff $tariffs
 * @method static Builder|TariffRegionCity newModelQuery()
 * @method static Builder|TariffRegionCity newQuery()
 * @method static Builder|TariffRegionCity query()
 * @method static Builder|TariffRegionCity whereBack($value)
 * @method static Builder|TariffRegionCity whereBackMinute($value)
 * @method static Builder|TariffRegionCity whereCreatedAt($value)
 * @method static Builder|TariffRegionCity whereFreeWait($value)
 * @method static Builder|TariffRegionCity whereFreeWaitEveryStop($value)
 * @method static Builder|TariffRegionCity whereIntent($value)
 * @method static Builder|TariffRegionCity whereMinimalPrice($value)
 * @method static Builder|TariffRegionCity wherePaidWait($value)
 * @method static Builder|TariffRegionCity wherePaidWaitEveryStop($value)
 * @method static Builder|TariffRegionCity wherePrice($value)
 * @method static Builder|TariffRegionCity wherePriceMinute($value)
 * @method static Builder|TariffRegionCity whereRoundingPrice($value)
 * @method static Builder|TariffRegionCity whereSitPriceKm($value)
 * @method static Builder|TariffRegionCity whereSitPriceMinute($value)
 * @method static Builder|TariffRegionCity whereTariffBehindCityId($value)
 * @method static Builder|TariffRegionCity whereTariffId($value)
 * @method static Builder|TariffRegionCity whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property int $tariff_region_city_id
 * @property float|null $price_km
 * @property float|null $price_min
 * @property int|null $sitting_fee
 * @property int|null $free_wait_stop_minutes
 * @property float|null $paid_wait_stop_minute
 * @property int|null $enable_speed_wait
 * @property int|null $speed_wait_limit
 * @property float|null $speed_wait_price_minute
 * @property-read Tariff $tariff
 * @property-read Tariff|null $to_tariff
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|TariffRegionCity whereEnableSpeedWait($value)
 * @method static Builder|TariffRegionCity whereFreeWaitStopMinutes($value)
 * @method static Builder|TariffRegionCity wherePaidWaitStopMinute($value)
 * @method static Builder|TariffRegionCity wherePriceKm($value)
 * @method static Builder|TariffRegionCity wherePriceMin($value)
 * @method static Builder|TariffRegionCity whereSittingFee($value)
 * @method static Builder|TariffRegionCity whereSpeedWaitLimit($value)
 * @method static Builder|TariffRegionCity whereSpeedWaitPriceMinute($value)
 * @method static Builder|TariffRegionCity whereTariffRegionCityId($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $minimal_distance_value
 * @property int|null $minimal_duration_value
 * @method static Builder|TariffRegionCity whereMinimalDistanceValue($value)
 * @method static Builder|TariffRegionCity whereMinimalDurationValue($value)
 * @property int|null $change_initial_price_percent
 * @property int $merge_km_minute
 * @method static Builder|TariffRegionCity whereChangeInitialPricePercent($value)
 * @method static Builder|TariffRegionCity whereMergeKmMinute($value)
 * @property int|null $area_id
 * @property int $price_type_id
 * @property int $sit_type_id
 * @property string|null $sit_fix_price
 * @property-read Area|null $area
 * @property-read TariffRegionBehind|null $behind
 * @property-read TariffPriceType $tariff_type
 * @property string $cancel_fee
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|TariffRegionCity whereAreaId($value)
 * @method static Builder|TariffRegionCity whereCancelFee($value)
 * @method static Builder|TariffRegionCity wherePriceTypeId($value)
 * @method static Builder|TariffRegionCity whereSitFixPrice($value)
 * @method static Builder|TariffRegionCity whereSitTypeId($value)
 * @property-read \Src\Models\Tariff\TariffRentAlt|null $rent_alt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Src\Models\Tariff\TariffRent[] $tariff_rents
 * @property-read int|null $tariff_rents_count
 */
class TariffRegionCity extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'tariff_regions_cities';
    /**
     * @var string
     */
    protected $primaryKey = 'tariff_region_city_id';
    /**
     * @var array
     */
    protected $casts = [
        'price_km' => 'float',
        'price_min' => 'float',
        'sit_price_km' => 'float',
        'sit_price_minute' => 'float',
        'paid_wait_stop_minute' => 'float',
        'speed_wait_price_minute' => 'float'
    ];
    /**
     * @var array
     */
    protected $fillable = [
        'tariff_id',
        'area_id',
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
        'speed_wait_price_minute',
        'minimal_distance_value',
        'minimal_duration_value',
        'change_initial_price_percent',
        'merge_km_minute',
        'sit_type_id',
        'price_type_id',
    ];
    /**
     * @var string
     */
    protected string $map = 'tariffRegionCity';

    /**
     * @return BelongsTo
     */
    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class, 'tariff_id', 'tariff_id');
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
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id', 'area_id');
    }

    /**
     * @return HasOne
     */
    public function behind(): HasOne
    {
        return $this->hasOne(TariffRegionBehind::class, 'tariff_region_id');
    }

    /**
     * @return BelongsTo
     */
    public function tariff_type(): BelongsTo
    {
        return $this->belongsTo(TariffPriceType::class, 'tariff_type_id', 'sit_type_id');
    }

    /**
     * @return MorphOne
     */
    public function rent_alt(): MorphOne
    {
        return $this->morphOne(TariffRentAlt::class, 'rent_alt', 'alt_type', 'alt_id');
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
            'tariff_region_city_id',
        );
    }
}
