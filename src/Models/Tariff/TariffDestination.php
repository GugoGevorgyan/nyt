<?php

declare(strict_types=1);

namespace Src\Models\Tariff;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Car\CarClass;
use Src\Models\Location\Area;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * Class TariffDestination
 *
 * @package Src\Models
 * @property int $destination_id
 * @property int $tariff_id
 * @property int $car_class_id
 * @property int $price
 * @property int|null $free_wait
 * @property int|null $paid_wait
 * @property string $address_from
 * @property string $address_to
 * @property object|null $locations
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CarClass $carClass
 * @property-read Tariff $tariff
 * @method static Builder|TariffDestination newModelQuery()
 * @method static Builder|TariffDestination newQuery()
 * @method static Builder|TariffDestination query()
 * @method static Builder|TariffDestination whereAddressFrom($value)
 * @method static Builder|TariffDestination whereAddressTo($value)
 * @method static Builder|TariffDestination whereCarClassId($value)
 * @method static Builder|TariffDestination whereCreatedAt($value)
 * @method static Builder|TariffDestination whereDestinationId($value)
 * @method static Builder|TariffDestination whereFreeWait($value)
 * @method static Builder|TariffDestination whereLocations($value)
 * @method static Builder|TariffDestination wherePaidWait($value)
 * @method static Builder|TariffDestination wherePrice($value)
 * @method static Builder|TariffDestination whereTariffId($value)
 * @method static Builder|TariffDestination whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property int $tariff_destination_id
 * @property int|null $destination_from_id
 * @property int|null $destination_to_id
 * @property int|null $free_wait_stop_minutes
 * @property float|null $paid_wait_stop_minute
 * @property-read Collection|Area[] $areas
 * @property-read int|null $areas_count
 * @property-read Area|null $from_area
 * @property-read Area|null $to_area
 * @property-read Tariff|null $to_tariff
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|TariffDestination whereDestinationFromId($value)
 * @method static Builder|TariffDestination whereDestinationToId($value)
 * @method static Builder|TariffDestination whereFreeWaitStopMinutes($value)
 * @method static Builder|TariffDestination wherePaidWaitStopMinute($value)
 * @method static Builder|TariffDestination whereTariffDestinationId($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $change_initial_price_percent
 * @method static Builder|TariffDestination whereChangeInitialPricePercent($value)
 * @property int $price_type_id
 * @property int $sitting_fee
 * @property string $cancel_fee
 * @property string|null $sit_price_km
 * @property string|null $sit_fix_price
 * @property string|null $sit_price_minute
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|TariffDestination whereCancelFee($value)
 * @method static Builder|TariffDestination wherePriceTypeId($value)
 * @method static Builder|TariffDestination whereSitFixPrice($value)
 * @method static Builder|TariffDestination whereSitPriceKm($value)
 * @method static Builder|TariffDestination whereSitPriceMinute($value)
 * @method static Builder|TariffDestination whereSittingFee($value)
 */
class TariffDestination extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'tariff_destinations';
    /**
     * @var string
     */
    protected $primaryKey = 'tariff_destination_id';
    /**
     * @var array
     */
    protected $casts = ['price' => 'float'];
    /**
     * @var array
     */
    protected $fillable = [
        'tariff_id',
        'price',
        'price_type_id',
        'destination_from_id',
        'destination_to_id',
        'free_wait_stop_minutes',
        'paid_wait_stop_minute',
        'change_initial_price_percent',
        'sitting_fee',
        'sit_fix_price',
        'sit_price_km',
        'sit_price_minute'
    ];
    /**
     * @var string
     */
    protected string $map = 'tariffDestination';

    /**
     * @return BelongsTo
     */
    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class, 'tariff_id', 'tariff_id');
    }

    /**
     * @return BelongsToJson
     */
    public function carClass(): BelongsToJson
    {
        return $this->belongsToJson(CarClass::class, 'car_class->id', 'car_class_id');
    }

    /**
     * @return MorphToMany
     */
    public function areas(): MorphToMany
    {
        return $this->morphToMany(Area::class, 'areable');
    }

    /**
     * @return BelongsTo
     */
    public function from_area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'destination_from_id');
    }

    /**
     * @return BelongsTo
     */
    public function to_area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'destination_to_id');
    }

    /**
     * @return MorphOne
     */
    public function to_tariff(): MorphOne
    {
        return $this->morphOne(Tariff::class, 'current_tariff');
    }
}
