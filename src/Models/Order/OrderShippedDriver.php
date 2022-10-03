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
use Src\Models\Driver\DriverInfo;
use Src\Models\Driver\DriverLock;
use Src\Models\RatingSystem\EstimatedRating;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class OrderShippedDriver
 *
 * @package Src\Models\Driver
 * @property int $pre_order_data_id
 * @property int $driver_id
 * @property int $order_id
 * @property int $system_rating_driver_id
 * @property int|null $status_id
 * @property int $current
 * @property string $response_url_hash
 * @property string $unix
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Driver $driver
 * @property-read Order $order
 * @property-read OrderShippedStatus|null $status
 * @method static Builder|OrderShippedDriver newModelQuery()
 * @method static Builder|OrderShippedDriver newQuery()
 * @method static Builder|OrderShippedDriver query()
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|OrderShippedDriver whereCreatedAt($value)
 * @method static Builder|OrderShippedDriver whereCurrent($value)
 * @method static Builder|OrderShippedDriver whereDriverId($value)
 * @method static Builder|OrderShippedDriver whereOrderId($value)
 * @method static Builder|OrderShippedDriver wherePreOrderDataId($value)
 * @method static Builder|OrderShippedDriver whereResponseUrlHash($value)
 * @method static Builder|OrderShippedDriver whereStatusId($value)
 * @method static Builder|OrderShippedDriver whereSystemRatingDriverId($value)
 * @method static Builder|OrderShippedDriver whereUnix($value)
 * @method static Builder|OrderShippedDriver whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $order_shipped_driver_id
 * @method static Builder|OrderShippedDriver whereOrderingShipmentDriverId($value)
 * @property string $on_way_response_url_hash
 * @property string $in_place_response_url_hash
 * @method static Builder|OrderShippedDriver whereInPlaceResponseUrlHash($value)
 * @method static Builder|OrderShippedDriver whereOnWayResponseUrlHash($value)
 * @property string|null $accept_hash
 * @property string|null $on_way_hash
 * @property string|null $in_place_hash
 * @property string|null $in_order_hash
 * @property string|null $pause_hash
 * @property string|null $end_hash
 * @property string|null $update_cord_hash
 * @property-read Collection|OrderInProcessRoad[] $in_process_roads
 * @property-read int|null $in_process_roads_count
 * @property-read OrderOnWayRoad|null $on_way_road
 * @property-read Collection|OrderOnWayRoad[] $on_way_roads
 * @property-read int|null $on_way_roads_count
 * @property-read OrderInProcessRoad|null $process_road
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|OrderShippedDriver whereAcceptHash($value)
 * @method static Builder|OrderShippedDriver whereGoInOrderHash($value)
 * @method static Builder|OrderShippedDriver whereInPlaceHash($value)
 * @method static Builder|OrderShippedDriver whereOnWayHash($value)
 * @method static Builder|OrderShippedDriver whereOrderEndHash($value)
 * @method static Builder|OrderShippedDriver whereOrderPauseHash($value)
 * @method static Builder|OrderShippedDriver whereUpdateCordHash($value)
 * @method static Builder|OrderShippedDriver whereEndHash($value)
 * @method static Builder|OrderShippedDriver wherePauseHash($value)
 * @property string|null $reset_hash
 * @method static Builder|OrderShippedDriver whereResetHash($value)
 * @property int $estimated_rating_id
 * @property-read EstimatedRating $estimated_rating
 * @method static Builder|OrderShippedDriver whereEstimatedRatingId($value)
 * @property-read OrderInProcessRoad|null $in_process_road
 * @property int|null $distance
 * @property int|null $duration
 * @property array|null $road
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderShippedDriver whereDistance($value)
 * @method static Builder|OrderShippedDriver whereDuration($value)
 * @method static Builder|OrderShippedDriver whereOrderShippedDriverId($value)
 * @method static Builder|OrderShippedDriver whereRoad($value)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read OrderProcess|null $process
 * @property int|null $late
 * @method static Builder|OrderShippedDriver whereLate($value)
 * @property int $common
 * @property-read OrderAttach|null $attach
 * @method static Builder|OrderShippedDriver whereCommon($value)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|OrderShippedDriver whereInOrderHash($value)
 * @property-read \Src\Models\Order\PreOrder $preorder
 */
class OrderShippedDriver extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'order_shipped_drivers';
    /**
     * @var string
     */
    protected $primaryKey = 'order_shipped_driver_id';
    /**
     * @var array
     */
    protected $fillable = [
        'driver_id',
        'order_id',
        'estimated_rating_id',
        'status_id',
        'current',
        'common',
        'accept_hash',
        'on_way_hash',
        'in_place_hash',
        'in_order_hash',
        'pause_hash',
        'end_hash',
        'late'
    ];
    /**
     * @var string[]
     */
    protected $casts = ['current' => 'bool', 'common' => 'bool', 'late' => 'int'];

    /**
     * @return HasOneDeep
     */
    public function order_stages(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->order(), (new Order())->stage());
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * @return HasOneDeep
     */
    public function initial_order(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->order(), (new Order())->initial_data());
    }

    /**
     * @return HasOneDeep
     */
    public function initial_order_tariff(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->order(), (new Order())->initial_data(), (new OrderInitialData())->initial_tariff());
    }

    /**
     * @return HasOneDeep
     */
    public function second_order_tariff(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->order(), (new Order())->initial_data(), (new OrderInitialData())->second_tariff());
    }

    /**
     * @return HasMany
     */
    public function on_way_roads(): HasMany
    {
        return $this->hasMany(OrderOnWayRoad::class, 'shipment_driver_id');
    }

    /**
     * @return HasMany
     */
    public function in_process_roads(): HasMany
    {
        return $this->hasMany(OrderInProcessRoad::class, 'shipment_driver_id');
    }

    /**
     * @return HasOne
     */
    public function process(): HasOne
    {
        return $this->hasOne(OrderProcess::class, 'order_shipped_id', $this->primaryKey);
    }

    /**
     * @return HasOne
     */
    public function on_way_road(): HasOne
    {
        return $this->hasOne(OrderOnWayRoad::class, 'shipment_driver_id')->where('selected', '=', true);
    }

    /**
     * @return HasOne
     */
    public function in_process_road(): HasOne
    {
        return $this->hasOne(OrderInProcessRoad::class, 'shipment_driver_id')->where('selected', '=', true);
    }

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function driver_info(): BelongsToThrough
    {
        return $this->belongsToThrough(DriverInfo::class, Driver::class, '', null, [DriverInfo::class => 'driver_info_id', Driver::class => 'driver_id']);
    }

    /**
     * @return BelongsToThrough
     */
    public function driver_locked(): BelongsToThrough
    {
        return $this->belongsToThrough(DriverLock::class, Driver::class, null, '', [DriverLock::class => 'driver_id', Driver::class => 'driver_id']);
    }

    /**
     * @return BelongsTo
     */
    public function estimated_rating(): BelongsTo
    {
        return $this->belongsTo(EstimatedRating::class, 'estimated_rating_id');
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderShippedStatus::class, 'status_id');
    }

    /**
     * @return HasOne
     */
    public function attach(): HasOne
    {
        return $this->hasOne(OrderAttach::class, 'shipped_id', 'order_shipped_driver_id');
    }

    /**
     * @return BelongsTo
     */
    public function common(): BelongsTo
    {
        return $this->belongsTo(OrderCommon::class, 'order_id', 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function preorder(): BelongsTo
    {
        return $this->belongsTo(PreOrder::class, 'order_id', 'order_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function payment_type(): BelongsToThrough
    {
        return $this->belongsToThrough(
            PaymentType::class,
            Order::class,
            '',
            '',
            [PaymentType::class => 'payment_type_id', Order::class => 'order_id']
        );
    }
}
