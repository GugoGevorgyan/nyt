<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Driver\Driver;
use Src\Models\Location\Timezone;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class PreOrder
 *
 * @package Src\Models\Order
 * @property int $order_schedule_id
 * @property int $order_id
 * @property string|null $start_time
 * @property string|null $create_time
 * @property string|null $time
 * @property-read Order $order
 * @property-read int|null $schedule_drivers_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|PreOrder newModelQuery()
 * @method static Builder|PreOrder newQuery()
 * @method static Builder|PreOrder query()
 * @method static Builder|PreOrder whereCreateTime($value)
 * @method static Builder|PreOrder whereOrderId($value)
 * @method static Builder|PreOrder whereOrderScheduleId($value)
 * @method static Builder|PreOrder whereStartTime($value)
 * @method static Builder|PreOrder whereTime($value)
 * @method static Builder|PreOrder whereTimeZone($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $preorder_id
 * @property array|null $driver
 * @property int $diff_minute
 * @property int $accept
 * @property Carbon|null $accepted
 * @property Carbon $created_at
 * @property-read Collection|OrderShippedDriver[] $shipped
 * @property-read int|null $shipped_count
 * @method static Builder|PreOrder whereAccept($value)
 * @method static Builder|PreOrder whereAccepted($value)
 * @method static Builder|PreOrder whereCreatedAt($value)
 * @method static Builder|PreOrder whereDiffMinute($value)
 * @method static Builder|PreOrder whereDriver($value)
 * @method static Builder|PreOrder wherePreorderId($value)
 * @property Carbon|null $distribution_start
 * @property bool $active
 * @property int $changed
 * @property-read OrderInitialData $initial
 * @property-read Collection|OrderShippedDriver[] $shippeds
 * @property-read int|null $shippeds_count
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|PreOrder whereActive($value)
 * @method static Builder|PreOrder whereChanged($value)
 * @method static Builder|PreOrder whereDistributionStart($value)
 * @property bool|null $skip
 * @property-read \Src\Models\Order\OrderCommon|null $common
 * @property-read Collection|\Src\Models\Order\OrderCommon[] $commons
 * @property-read int|null $commons_count
 * @method static Builder|PreOrder whereSkip($value)
 */
class PreOrder extends ServiceModel
{
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'preorders';
    /**
     * @var string
     */
    protected $primaryKey = 'preorder_id';
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'create_time',
        'time',
        'diff_minute',
        'distribution_start',
        'active',
        'changed',
        'skip',
    ];
    /**
     * @var string[]
     */
    protected $casts = ['active' => 'bool', 'changed' => 'bool', 'skip' => 'bool'];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * @return BelongsToJson
     */
    public function drivers(): BelongsToJson
    {
        return $this->belongsToJson(Driver::class, 'driver->ids', 'driver_id');
    }

    /**
     * @return HasOneDeep
     */
    public function shipped_driver(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->shipped(), (new OrderShippedDriver())->driver());
    }

    /**
     * @return HasOne
     */
    public function shipped(): HasOne
    {
        return $this->hasOne(OrderShippedDriver::class, 'order_id', 'order_id')->latest();
    }

    /**
     * @return HasMany
     */
    public function shippeds(): HasMany
    {
        return $this->hasMany(OrderShippedDriver::class, 'order_id', 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function initial(): BelongsTo
    {
        return $this->belongsTo(OrderInitialData::class, 'order_id', 'order_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function location_zone(): BelongsToThrough
    {
        return $this->belongsToThrough(Timezone::class, Order::class, 'order_id', '', [Timezone::class => 'location_zone_id', Order::class => 'order_id']);
    }

    /**
     * @return BelongsToThrough
     */
    public function customer_zone(): BelongsToThrough
    {
        return $this->belongsToThrough(Timezone::class, Order::class, null, '', [Timezone::class => 'customer_zone_id', Order::class => 'order_id']);
    }

    /**
     * @return HasMany
     */
    public function commons(): HasMany
    {
        return $this->hasMany(OrderCommon::class, 'order_id', 'order_id');
    }

    /**
     * @return HasOne
     */
    public function common(): HasOne
    {
        return $this->hasOne(OrderCommon::class, 'order_id', 'order_id')->latest();
    }
}
