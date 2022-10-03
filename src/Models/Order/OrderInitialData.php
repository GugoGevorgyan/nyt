<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Tariff\Tariff;

/**
 * Class InitialOrderData
 *
 * @package Src\Models\Order
 * @property int $initial_order_data_id
 * @property int|null $order_id
 * @property int|null $initial_tariff_id
 * @property int|null $second_tariff_id
 * @property int $orderable_id
 * @property string $orderable_type
 * @property string|null $price
 * @property string|null $currency
 * @property int $sitting_fee
 * @property int $initial
 * @property string|null $distance
 * @property string|null $duration
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tariff|null $initial_tariff
 * @property-read Order|null $order
 * @property-read Model|Eloquent $orderable
 * @property-read Tariff|null $second_tariff
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData newQuery()
 * @method static Builder|OrderInitialData onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereInitialOrderDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereInitialTariffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereOrderableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereOrderableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereSecondTariffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereSittingFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereUpdatedAt($value)
 * @method static Builder|OrderInitialData withTrashed()
 * @method static Builder|OrderInitialData withoutTrashed()
 * @mixin Eloquent
 * @property string $lat
 * @property string $lut
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereLut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereDeletedAt($value)
 * @property int $order_initial_data_id
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereOrderInitialDataId($value)
 * @property int $region_id
 * @property int|null $city_id
 * @property int|null $behind
 * @property string|null $option_price
 * @property string|null $sitting_price
 * @property int $waiting_cancel
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereBehind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereOptionPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereSittingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereWaitingCancel($value)
 */
class OrderInitialData extends ServiceModel
{
    protected const LATITUDE = 'lat';
    protected const LONGITUDE = 'lut';
    protected static bool $kilometers = false;

    /**
     * @var string
     */
    protected $table = 'order_initial_data';
    /**
     * @var string
     */
    protected $primaryKey = 'order_initial_data_id';
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'orderable_id',
        'orderable_type',
        'initial_tariff_id',
        'second_tariff_id',
        'behind',
        'price',
        'sitting_price',
        'option_price',
        'live_price',
        'price_passed',
        'sit_price',
        'initial',
        'currency',
        'distance',
        'duration',
        'region_id',
        'city_id',
        'waiting_cancel',
        'lat',
        'lut',
    ];
    /**
     * @var array
     */
    protected $casts = [
        'price_passed' => 'datetime',
        'lat' => 'float',
        'lut' => 'float', /*'price' => 'float',*/
        'sitting_fee' => 'bool',
        'initial' => 'bool',
        'distance' => 'float'
    ];
    /**
     * @var string[]
     */
    protected $dates = ['price_passed'];

    /**
     * @return MorphTo
     */
    public function orderable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function initial_tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class, 'initial_tariff_id', 'tariff_id');
    }

    /**
     * @return BelongsTo
     */
    public function second_tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class, 'second_tariff_id', 'tariff_id');
    }
}
