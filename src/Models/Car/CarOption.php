<?php

declare(strict_types=1);

namespace Src\Models\Car;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Order\Order;
use Staudenmeir\EloquentJsonRelations\Relations\HasManyJson;

/**
 * Class CarOption
 *
 * @package Src\Models\Car
 * @property int $car_option_id
 * @property string|null $option
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CarOption newModelQuery()
 * @method static Builder|CarOption newQuery()
 * @method static Builder|CarOption query()
 * @method static Builder|CarOption whereCarOptionId($value)
 * @method static Builder|CarOption whereCreatedAt($value)
 * @method static Builder|CarOption whereOption($value)
 * @method static Builder|CarOption wherePrice($value)
 * @method static Builder|CarOption whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string $name
 * @method static Builder|CarOption whereName($value)
 * @property string $value
 * @method static Builder|CarOption whereValue($value)
 * @property-read ClassOptionTariff $class_option_tariff
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class CarOption extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'car_options';
    /**
     * @var string
     */
    protected $primaryKey = 'car_option_id';
    /**
     * @var array
     */
    protected $fillable = ['option', 'price', 'name'];
    /**
     * @var array
     */
    protected $dates = ['created_at'];

    /**
     * @return HasManyJson
     */
    public function order(): HasManyJson
    {
        return $this->hasManyJson(Order::class, 'car_option->ids');
    }

    /**
     * @return BelongsTo
     */
    public function class_option_tariff(): BelongsTo
    {
        return $this->belongsTo(ClassOptionTariff::class, 'car_option_id', 'option_id');
    }
}
