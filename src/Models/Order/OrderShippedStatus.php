<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class OrderShippedStatus
 *
 * @package Src\Models\Order
 * @property int $pre_order_data_status_id
 * @property int $status
 * @property string $name
 * @property string $color
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|OrderShippedDriver[] $pre_orders
 * @property-read int|null $pre_orders_count
 * @method static Builder|OrderShippedStatus newModelQuery()
 * @method static Builder|OrderShippedStatus newQuery()
 * @method static Builder|OrderShippedStatus query()
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|OrderShippedStatus whereColor($value)
 * @method static Builder|OrderShippedStatus whereCreatedAt($value)
 * @method static Builder|OrderShippedStatus whereDescription($value)
 * @method static Builder|OrderShippedStatus whereName($value)
 * @method static Builder|OrderShippedStatus wherePreOrderDataStatusId($value)
 * @method static Builder|OrderShippedStatus whereStatus($value)
 * @method static Builder|OrderShippedStatus whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $ordering_shipment_driver_status_id
 * @property-read Collection|OrderShippedDriver[] $orders_shipment_driver
 * @property-read int|null $orders_shipment_driver_count
 * @method static Builder|OrderShippedStatus whereOrderingShipmentDriverStatusId($value)
 * @method static Builder|ServiceModel except($values = [])
 * @property int $order_shipped_status_id
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderShippedStatus whereOrderShippedStatusId($value)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string $text
 * @method static Builder|OrderShippedStatus whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class OrderShippedStatus extends ServiceModel
{
    /**
     *
     */
    public const PRE_PENDING = 1;
    /**
     *
     */
    public const PRE_ACCEPTED = 2;
    /**
     *
     */
    public const PRE_REJECTED = 3;
    /**
     *
     */
    public const PRE_CANCELED = 4;
    /**
     *
     */
    public const PRE_MANUAL = 5;
    /**
     *
     */
    public const PRE_UNPIN = 6;


    /**
     * @var string
     */
    protected $table = 'order_shipped_status';

    /**
     * @var string
     */
    protected $primaryKey = 'order_shipped_status_id';

    /**
     * @var array
     */
    protected $fillable = ['status', 'name', 'color', 'description'];

    /**
     * @return HasMany
     */
    public function orders_shipment_driver(): HasMany
    {
        return $this->hasMany(OrderShippedDriver::class, 'status_id');
    }
}
