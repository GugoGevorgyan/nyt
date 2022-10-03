<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Car\Car;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverInfo;
use Src\Models\Tariff\Tariff;
use Src\Models\Terminal\Waybill;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class CompletedOrder
 *
 * @package Src\Models\Order
 * @property int $completed_order_id
 * @property int|null $order_id
 * @property int|null $driver_id
 * @property string|null $distance
 * @property string|null $duration
 * @property float|null $cost
 * @property object $trajectory
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int|null $assessments_count
 * @property-read Driver $driver
 * @property-read Collection|OrderFeedback[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read Order|null $order
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|CompletedOrder newModelQuery()
 * @method static Builder|CompletedOrder newQuery()
 * @method static Builder|CompletedOrder query()
 * @method static Builder|CompletedOrder whereCompletedOrderId($value)
 * @method static Builder|CompletedOrder whereCost($value)
 * @method static Builder|CompletedOrder whereCreatedAt($value)
 * @method static Builder|CompletedOrder whereDistance($value)
 * @method static Builder|CompletedOrder whereDriverId($value)
 * @method static Builder|CompletedOrder whereDuration($value)
 * @method static Builder|CompletedOrder whereOrderId($value)
 * @method static Builder|CompletedOrder whereTrajectory($value)
 * @method static Builder|CompletedOrder whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Waybill|null $waybill
 * @property int|null $car_id
 * @property-read Car|null $car
 * @method static Builder|CompletedOrder whereCarId($value)
 * @property string|null $destination_address
 * @method static Builder|CompletedOrder whereDestinationAddress($value)
 * @property string|null $in_price
 * @property string|null $out_price
 * @property mixed|null $in_trajectory
 * @property mixed|null $out_trajectory
 * @property string|null $in_distance
 * @property string|null $out_distance
 * @property string|null $in_duration
 * @property string|null $out_duration
 * @property-read Collection|OrderFeedback[] $assessments
 * @property-read OrderInitialData|null $initial
 * @property string|null $in_distance_price
 * @property string|null $in_duration_price
 * @property string|null $out_distance_price
 * @property string|null $out_duration_price
 * @property bool $changed
 * @property-read Collection|CompletedOrderChange[] $changes
 * @property-read int|null $changes_count
 * @property string $distance_price
 * @property string $duration_price
 * @property-read CompletedOrderCrossing|null $crossing
 * @method static Builder|CompletedOrder whereDistancePrice($value)
 * @method static Builder|CompletedOrder whereDurationPrice($value)
 * @property string|null $destination_lat
 * @property string|null $destination_lut
 * @property-read \Src\Models\Order\OrderProcess|null $process
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|CompletedOrder whereChanged($value)
 * @method static Builder|CompletedOrder whereDestinationLat($value)
 * @method static Builder|CompletedOrder whereDestinationLut($value)
 */
class CompletedOrder extends ServiceModel
{
    //*Feedback Statuses *//
    public const FEEDBACK_STATUS_NEW = 'new';
    public const FEEDBACK_STATUS_PROCESSING = 'processing';
    public const FEEDBACK_STATUS_DONE = 'done';

    /**
     * @var string
     */
    protected $table = 'completed_orders';
    /**
     * @var string
     */
    protected $primaryKey = 'completed_order_id';
    /**
     * @var string
     */
    protected string $map = 'completedOrder';
    /**
     * @var array
     */
    protected $casts = [
        'trajectory' => 'array',
        'cost' => 'float',
        'distance' => 'float',
        'duration' => 'integer',
        'in_trajectory' => 'array',
        'out_trajectory' => 'array',
        'changed' => 'bool',
    ];
    /**
     * @var string[]
     */
    protected $attributes = ['trajectory' => '[]'];
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'driver_id',
        'car_id',
        'distance',
        'duration',
        'cost',
        'destination_address',
        'destination_lat',
        'destination_lut',
        'distance_price',
        'duration_price',
        'trajectory',
        'changed',
    ];

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
        return $this->belongsToThrough(DriverInfo::class, Driver::class, 'driver_id', '',
            [DriverInfo::class => 'driver_info_id', Driver::class => 'driver_id']);
    }

    /**
     * @return BelongsTo
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id', 'car_id');
    }

    /**
     * @return MorphMany
     */
    public function feedbacks(): MorphMany
    {
        return $this->morphMany(OrderFeedback::class, 'orderable', 'orderable_type', 'orderable_id');
    }

    /**
     * @return HasOneDeep
     */
    public function stage(): HasOneDeep
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
     * @return HasOneThrough
     */
    public function process(): HasOneThrough
    {
        return $this->hasOneThrough(OrderProcess::class, OrderShippedDriver::class, 'order_id', 'order_shipped_id', 'order_id', 'order_shipped_driver_id');
    }

    /**
     * @return HasOneDeep
     */
    public function corporate(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->order(), (new Order())->corporate());
    }

    /**
     * @return HasOneThrough
     */
    public function waybill(): HasOneThrough
    {
        return $this->hasOneThrough(Waybill::class, Driver::class, 'driver_id', 'driver_id');
    }

    /**
     * @return MorphMany
     */
    public function assessments(): MorphMany
    {
        return $this->morphMany(OrderFeedback::class, 'orderable')->where('assessment', '!=', 0);
    }

    /**
     * @return BelongsToThrough
     */
    public function payment_type(): BelongsToThrough
    {
        return $this->belongsToThrough(PaymentType::class, Order::class, 'order_id', '', [PaymentType::class => 'payment_type_id', Order::class => 'order_id']);
    }

    /**
     * @return BelongsToThrough
     */
    public function tariff(): BelongsToThrough
    {
        return $this->belongsToThrough(Tariff::class, OrderInitialData::class, null, '',
            [Tariff::class => 'initial_tariff_id', OrderInitialData::class => 'order_id']);
    }

    /**
     * @return BelongsTo
     */
    public function initial(): BelongsTo
    {
        return $this->belongsTo(OrderInitialData::class, 'order_id', 'order_id');
    }

    /**
     * @return HasMany
     */
    public function changes(): HasMany
    {
        return $this->hasMany(CompletedOrderChange::class, 'completed_id', $this->primaryKey);
    }

    /**
     * @return HasOne
     */
    public function crossing(): HasOne
    {
        return $this->hasOne(CompletedOrderCrossing::class, 'completed_id', 'completed_order_id');
    }
}
