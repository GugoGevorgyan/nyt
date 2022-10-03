<?php

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverInfo;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Src\Models\Order\OrderAttach
 *
 * @property int $order_attach_id
 * @property int $order_id
 * @property int|null $driver_id
 * @property int|null $system_worker_id
 * @property int|null $accepted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|OrderAttach newModelQuery()
 * @method static Builder|OrderAttach newQuery()
 * @method static \Illuminate\Database\Query\Builder|OrderAttach onlyTrashed()
 * @method static Builder|OrderAttach query()
 * @method static Builder|OrderAttach whereAccepted($value)
 * @method static Builder|OrderAttach whereCreatedAt($value)
 * @method static Builder|OrderAttach whereDriverId($value)
 * @method static Builder|OrderAttach whereOrderAttachId($value)
 * @method static Builder|OrderAttach whereOrderId($value)
 * @method static Builder|OrderAttach whereSystemWorkerId($value)
 * @method static Builder|OrderAttach whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|OrderAttach withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OrderAttach withoutTrashed()
 * @mixin Eloquent
 * @property int|null $shipped_id
 * @property-read OrderShippedDriver|null $shipped
 * @method static Builder|OrderAttach whereShippedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 */
class OrderAttach extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'order_attaches';
    /**
     * @var string
     */
    protected $primaryKey = 'order_attach_id';
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'driver_id',
        'system_worker_id',
        'shipped_id',
        'accepted'
    ];

    /**
     * @return BelongsTo
     */
    public function shipped(): BelongsTo
    {
        return $this->belongsTo(OrderShippedDriver::class, 'shipped_id', 'order_shipped_driver_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function driver_info(): BelongsToThrough
    {
        return $this->belongsToThrough(DriverInfo::class, Driver::class, 'driver_id', '',
            [DriverInfo::class => 'driver_info_id', Driver::class => 'driver_id']);
    }
}
