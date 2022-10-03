<?php

declare(strict_types=1);

namespace Src\Models\Order;

use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Order\OrderRent
 *
 * @property int $order_rent_id
 * @property int $order_id
 * @property int $hours
 * @property int|null $after_rent_time MINUTE
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent whereAfterRentTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent whereHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent whereOrderRentId($value)
 * @mixin \Eloquent
 */
class OrderRent extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'order_rent';

    /**
     * @var string
     */
    protected $primaryKey = 'order_rent_id';

    /**
     * @var array
     */
    protected $fillable = [
        'order_rent_id',
        'order_id',
        'hours'
    ];


}
