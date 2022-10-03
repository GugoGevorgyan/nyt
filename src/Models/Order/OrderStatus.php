<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Order\OrderStatus
 *
 * @property int $order_status_id
 * @property string $status
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @method static Builder|OrderStatus newModelQuery()
 * @method static Builder|OrderStatus newQuery()
 * @method static Builder|OrderStatus query()
 * @method static Builder|OrderStatus whereOrderStatusId($value)
 * @method static Builder|OrderStatus whereStatus($value)
 * @mixin Eloquent
 * @property string $color
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|OrderStatus whereColor($value)
 * @property string $name
 * @property string $text
 * @method static Builder|OrderStatus whereName($value)
 * @method static Builder|OrderStatus whereText($value)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class OrderStatus extends ServiceModel
{
    /**
     *
     */
    public const ORDER_PENDING = 1;
    /**
     *
     */
    public const ORDER_IN_PROCESS = 2;
    /**
     *
     */
    public const ORDER_PAUSED = 3;
    /**
     *
     */
    public const ORDER_COMPLETED = 4;
    /**
     *
     */
    public const ORDER_CANCELED = 5;


    /**
     * @var string
     */
    protected $table = 'order_statuses';

    /**
     * @var string
     */
    protected $primaryKey = 'order_status_id';

    /**
     * @var array
     */
    protected $fillable = ['status', 'name', 'color'];

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'status_id', 'order_status_id');
    }
}
