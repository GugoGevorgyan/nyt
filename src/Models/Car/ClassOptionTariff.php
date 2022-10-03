<?php

declare(strict_types=1);

namespace Src\Models\Car;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Tariff\Tariff;

/**
 * Class ClassOptionTariff
 *
 * @package Src\Models\Car
 * @property int $class_option_tariff_id
 * @property int $tariff_id
 * @property int $class_id
 * @property int $option_id
 * @property float $price
 * @property Carbon $created_at
 * @property-read CarClass $car_class
 * @property-read CarOption $option
 * @property-read Tariff $tariff
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ClassOptionTariff newModelQuery()
 * @method static Builder|ClassOptionTariff newQuery()
 * @method static Builder|ClassOptionTariff query()
 * @method static Builder|ClassOptionTariff whereClassId($value)
 * @method static Builder|ClassOptionTariff whereClassOptionTariffId($value)
 * @method static Builder|ClassOptionTariff whereCreatedAt($value)
 * @method static Builder|ClassOptionTariff whereOptionId($value)
 * @method static Builder|ClassOptionTariff wherePrice($value)
 * @method static Builder|ClassOptionTariff whereTariffId($value)
 * @mixin Eloquent
 */
class ClassOptionTariff extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'class_option_tariff';
    /**
     * @var string
     */
    protected $primaryKey = 'class_option_tariff_id';
    /**
     * @var string[]
     */
    protected $fillable = ['class_id', 'option_id', 'tariff_id', 'price'];
    /**
     * @var string[]
     */
    protected $casts = ['price' => 'float'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     * @return BelongsTo
     */
    public function car_class(): BelongsTo
    {
        return $this->belongsTo(CarClass::class, 'class_id', 'car_class_id');
    }

    /**
     * @return BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(CarOption::class, 'option_id', 'car_option_id');
    }

    /**
     * @return BelongsTo
     */
    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class, 'tariff_id', 'tariff_id');
    }
}
