<?php

declare(strict_types=1);

namespace Src\Models\Car;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Models\Corporate\CorporateClient;
use Src\Models\Order\Order;
use Src\Models\Tariff\Tariff;
use Src\Models\Tariff\TariffDestination;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Staudenmeir\EloquentJsonRelations\Relations\HasManyJson;

/**
 * Class CarClass
 *
 * @package Src\Models\Car
 * @property int $car_class_id
 * @property string|null $class_name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Car[] $cars
 * @method static Builder|CarClass newModelQuery()
 * @method static Builder|CarClass newQuery()
 * @method static Builder|CarClass query()
 * @method static Builder|CarClass whereCarClassId($value)
 * @method static Builder|CarClass whereClassName($value)
 * @method static Builder|CarClass whereCreatedAt($value)
 * @method static Builder|CarClass whereDescription($value)
 * @method static Builder|CarClass whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|Order[] $orders
 * @property-read int|null $cars_count
 * @property-read Collection|TariffDestination[] $destinations
 * @property-read int|null $destinations_count
 * @property-read int|null $orders_count
 * @property-read Collection|Tariff[] $tariffs
 * @property-read int|null $tariffs_count
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property string|null $image
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|CarClass whereImage($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string|null $name
 * @property-read \Src\Models\Car\ClassOptionTariff $class_option_tariff
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|CarClass whereName($value)
 */
class CarClass extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'cars_class';
    /**
     * @var string
     */
    protected $primaryKey = 'car_class_id';
    /**
     * @var array
     */
    protected $fillable = ['class_name', 'description', 'class', 'image', 'name'];


    /**
     * @return HasMany
     */
    public function cars(): HasMany
    {
        return $this->hasManyJson(Car::class, 'class->ids');
    }

    /**
     * @return HasManyJson
     */
    public function corporateClients(): HasManyJson
    {
        return $this->hasManyJson(CorporateClient::class, 'car_classes_ids->ids', 'car_class_id');
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'car_class_id', 'car_class_id');
    }

    /**
     * @return HasMany
     */
    public function tariffs(): HasMany
    {
        return $this->hasMany(Tariff::class, 'car_class_id', 'car_class_id');
    }

    /**
     * @return HasMany
     */
    public function destinations(): HasMany
    {
        return $this->hasMany(TariffDestination::class, 'car_class_id', 'car_class_id');
    }

    /**
     * @return BelongsTo
     */
    public function class_option_tariff(): BelongsTo
    {
        return $this->belongsTo(ClassOptionTariff::class, 'car_class_id', 'class_id');
    }
}
