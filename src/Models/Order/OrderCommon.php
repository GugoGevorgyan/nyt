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
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * Class OrderCommon
 *
 * @package Src\Order
 * @property int $order_common_id
 * @property int $order_id
 * @property array $driver_ids
 * @property int|null $accept
 * @property int $emergency
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $order
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderCommon newModelQuery()
 * @method static Builder|OrderCommon newQuery()
 * @method static Builder|OrderCommon query()
 * @method static Builder|OrderCommon whereAccept($value)
 * @method static Builder|OrderCommon whereCreatedAt($value)
 * @method static Builder|OrderCommon whereDriverIds($value)
 * @method static Builder|OrderCommon whereEmergency($value)
 * @method static Builder|OrderCommon whereOrderCommonId($value)
 * @method static Builder|OrderCommon whereOrderId($value)
 * @method static Builder|OrderCommon whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property array $driver
 * @property-read Collection|OrderShippedDriver[] $shipped
 * @property-read int|null $shipped_count
 * @method static Builder|OrderCommon whereDriver($value)
 * @property bool $manual
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|OrderCommon whereManual($value)
 * @property int|null $filter_type
 * @property int|null $distance
 * @property string|null $accepted
 * @property bool|null $active
 * @property-read PreOrder $preorder
 * @property-read OrderShippedDriver|null $ship
 * @method static Builder|OrderCommon whereAccepted($value)
 * @method static Builder|OrderCommon whereActive($value)
 * @method static Builder|OrderCommon whereDistance($value)
 * @method static Builder|OrderCommon whereFilterType($value)
 */
class OrderCommon extends ServiceModel
{
    public const TYPE_ONLINE = 5;
    public const TYPE_ALL = 6;

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'orders_common';
    /**
     * @var string
     */
    protected $primaryKey = 'order_common_id';
    /**
     * @var string[]
     */
    protected $fillable = ['order_id', 'driver', 'accept', 'accepted', 'emergency', 'manual', 'active', 'filter_type', 'distance'];
    /**
     * @var string[]
     */
    protected $casts = ['driver' => 'json', 'manual' => 'bool', 'emergency' => 'bool', 'accept' => 'bool', 'active' => 'bool'];
    /**
     * @var string[]
     */
    protected $attributes = ['driver' => '{"ids": []}'];

    /**
     * @return BelongsToJson
     */
    public function drivers(): BelongsToJson
    {
        return $this->belongsToJson(Driver::class, 'driver_ids->id', 'driver_id');
    }

    /**
     * @return HasMany
     */
    public function shipped(): HasMany
    {
        return $this->hasMany(OrderShippedDriver::class, 'order_id', 'order_id');
    }

    /**
     * @return HasOne
     */
    public function ship(): HasOne
    {
        return $this->hasOne(OrderShippedDriver::class, 'order_id', 'order_id')->latest();
    }

    /**
     * @return BelongsTo
     */
    public function preorder(): BelongsTo
    {
        return $this->belongsTo(PreOrder::class, 'order_id', 'order_id');
    }

    /**
     * @return HasOne
     */
    public function initial(): HasOne
    {
        return $this->hasOne(OrderInitialData::class, 'order_id', 'order_id');
    }

    /**
     * @return HasOneDeep
     */
    public function company(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->order(), (new Order())->company());
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
