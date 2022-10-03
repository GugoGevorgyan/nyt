<?php

declare(strict_types=1);

namespace Src\Models\Tariff;


use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Car\CarClass;
use Src\Models\Car\ClassOptionTariff;
use Src\Models\Corporate\Company;
use Src\Models\Location\City;
use Src\Models\Location\Country;
use Src\Models\Location\Region;
use Src\Models\Order\OrderInitialData;
use Src\Scopes\TariffScope;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * Class Tariff
 *
 * @package Src\Models
 * @property int $tariff_id
 * @property int $region_id
 * @property int $car_class_id
 * @property int $tariff_type_id
 * @property string $name
 * @property int $is_default
 * @property int|null $price
 * @property int|null $price_minute
 * @property int|null $minimal_price
 * @property int|null $intent_minute
 * @property int|null $intent
 * @property int|null $sit_price_km
 * @property int|null $sit_price_minute
 * @property int|null $free_wait_every_stop
 * @property int|null $paid_wait_every_stop
 * @property int|null $enable_speed_wait
 * @property string|null $rounding_price
 * @property int|null $free_wait
 * @property int|null $paid_wait
 * @property int $tool_roads_client
 * @property int $paid_parking_client
 * @property string|null $date_to
 * @property int $status
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TariffRegionCity $behindCity
 * @property-read TariffRegionBehind $behindMkad
 * @property-read CarClass $cars
 * @property-read Collection|City[] $cities
 * @property-read int|null $cities_count
 * @property-read Collection|Company[] $companies
 * @property-read int|null $companies_count
 * @property-read Collection|TariffDestination[] $destinations
 * @property-read int|null $destinations_count
 * @property-read Region $region
 * @property-read TariffPriceType $tariffType
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff newQuery()
 * @method static Builder|Tariff onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereCarClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereEnableSpeedWait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereFreeWait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereFreeWaitEveryStop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereIntent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereIntentMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereMinimalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePaidParkingClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePaidWait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePaidWaitEveryStop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePriceMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereRoundingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereSitPriceKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereSitPriceMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereTariffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereTariffTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereToolRoadsClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereUpdatedAt($value)
 * @method static Builder|Tariff withTrashed()
 * @method static Builder|Tariff withoutTrashed()
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property int $payment_type_id
 * @property array|null $city
 * @property int|null $tariffable_id
 * @property string|null $tariffable_type
 * @property int|null $free_wait_minutes initial wait minutes
 * @property float|null $paid_wait_minute initial wait price every minute
 * @property string|null $date_from
 * @property-read Model|Eloquent $current_tariff
 * @property-read OrderInitialData|null $initial
 * @property-read OrderInitialData|null $secondary
 * @property-read TariffRegionBehind|null $tariff_behind
 * @property-read Collection|TariffDestination[] $tariff_destinations
 * @property-read int|null $tariff_destinations_count
 * @property-read TariffRegionCity|null $tariff_region
 * @property-read TariffPriceType $tariff_type
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereFreeWaitMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePaidWaitMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereTariffableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereTariffableType($value)
 * @property int|null $country_id
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereCountryId($value)
 * @property-read Country|null $country
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read TariffRegionCity|null $tariff_behind_region
 * @property-read CarClass $car_class
 * @property-read ClassOptionTariff $class_option
 * @property float|null $diff_percent
 * @property-read TariffRent|null $rent
 * @property-read TariffDestination|null $tariff_destination
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereDiffPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereRegion($value)
 * @property string|null $limit_manually_cost
 * @property-read int|null $class_option_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff getArea($tariff_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereLimitManuallyCost($value)
 */
class Tariff extends ServiceModel
{
    public const BEHIND_NOTE = 0;
    public const BEHIND_FROM = 1;
    public const BEHIND_TO = 2;
    public const BEHIND_DOUBLE = 3;

    /**
     * @var string
     */
    protected $table = 'tariffs';
    /**
     * @var string
     */
    protected $primaryKey = 'tariff_id';
    /**
     * @var array
     */
    protected $casts = ['city' => 'json', 'region' => 'json', 'minimal_price' => 'float', 'paid_wait_minute' => 'float', 'diff_percent' => 'float'];
    /**
     * @var string[]
     */
    protected $attributes = ['city' => '{"ids": []}'];
    /**
     * @var array
     */
    protected $fillable = [
        'country_id',
        'region',
        'city',
        'name',
        'car_class_id',
        'tariff_type_id',
        'payment_type_id',
        'paid_parking_client',
        'tool_roads_client',
        'is_default',
        'status',
        'free_wait_minutes',
        'paid_wait_minute',
        'minimal_price',
        'rounding_price',
        'date_from',
        'date_to',
        'tariffable_id',
        'tariffable_type',
        'diff_percent',
        'limit_manually_cost',
    ];
    /**
     * @var string
     */
    protected string $map = 'tariff';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new TariffScope());
    }

    /**
     * @return BelongsTo
     */
    public function cars(): BelongsTo
    {
        return $this->belongsTo(CarClass::class, 'car_class_id', 'car_class_id');
    }

    /**
     * @return BelongsTo
     */
    public function tariff_type(): BelongsTo
    {
        return $this->belongsTo(TariffPriceType::class, 'tariff_type_id', 'tariff_type_id');
    }

    /**
     * @return BelongsToMany
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, (new CompanyTariff())->getTable(), 'tariff_id', 'company_id');
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * @return BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsToJson(Region::class, 'tariff->ids', 'region_id');
    }

    /**
     * @return BelongsToJson
     */
    public function cities(): BelongsToJson
    {
        return $this->belongsToJson(City::class, 'city->ids');
    }

    /**
     * @return HasOne
     */
    public function tariff_behind(): HasOne
    {
        return $this->hasOne(TariffRegionBehind::class, 'tariff_id', 'tariff_id');
    }

    /**
     * @return HasOne
     */
    public function tariff_destination(): HasOne
    {
        return $this->hasOne(TariffDestination::class, 'tariff_id', 'tariff_id');
    }

    /**
     * @return HasOneDeep
     */
    public function area(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->tariff_region(), (new TariffRegionCity())->area());
    }

    /**
     * @return HasOne
     */
    public function tariff_region(): HasOne
    {
        return $this->hasOne(TariffRegionCity::class, 'tariff_id', 'tariff_id');
    }

    /**
     * @return HasOne
     */
    public function initial(): HasOne
    {
        return $this->hasOne(OrderInitialData::class, 'initial_tariff_id', 'tariff_id');
    }

    /**
     * @return HasOne
     */
    public function rent(): HasOne
    {
        return $this->hasOne(TariffRent::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return MorphTo
     */
    public function current_tariff(): MorphTo
    {
        return $this->morphTo('current_tariff', 'tariffable_type', 'tariffable_id');
    }

    /**
     * @return BelongsTo
     */
    public function car_class(): BelongsTo
    {
        return $this->belongsTo(CarClass::class, 'car_class_id', 'car_class_id');
    }

    /**
     * @return HasMany
     */
    public function class_option(): HasMany
    {
        return $this->hasMany(ClassOptionTariff::class, 'tariff_id', 'tariff_id');
    }

    public function rent_alt()
    {
        return $this->morphMany();
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param $tariff_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetArea(\Illuminate\Database\Eloquent\Builder $query, $tariff_id): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereRaw('select distinct t.tariff_id, a.area, a.area_id
from (tariffs as t, areas as a)
         inner join (tariff_rents, tariff_regions_cities, areas)
                    on tariff_regions_cities.area_id = a.area_id and tariff_regions_cities.tariff_id = t.tariff_id
                        or tariff_rents.area_id = a.area_id and tariff_rents.tariff_id = t.tariff_id
where t.tariff_id = '.$tariff_id);
    }
}
