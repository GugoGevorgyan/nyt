<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Car\Car;
use Src\Models\Car\CarClass;
use Src\Models\Car\CarOption;
use Src\Models\Chat\OrderConversationTalk;
use Src\Models\Client\Client;
use Src\Models\Complaint\Complaint;
use Src\Models\Corporate\AdminCorporate;
use Src\Models\Driver\Driver;
use Src\Models\Franchise\Franchise;
use Src\Models\Franchise\FranchiseTransaction;
use Src\Models\Location\Timezone;
use Src\Models\Monitor\Address;
use Src\Models\RatingSystem\EstimatedRating;
use Src\Models\SystemUsers\SystemWorker;
use Src\Models\SystemUsers\WorkerDispatcher;
use Src\Models\SystemUsers\WorkerOperator;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * Class Order
 *
 * @package Src\Models\Order
 * @property int $order_id
 * @property int|null $car_class_id
 * @property int|null $order_type_id
 * @property int|null $payment_type_id
 * @property int|null $status_id
 * @property int|null $client_id
 * @property int|null $customer_id
 * @property string|null $customer_type
 * @property array|null $franchisee
 * @property array|null $car_option
 * @property array $from_coordinates
 * @property array|null $to_coordinates
 * @property string $address_from
 * @property string|null $address_to
 * @property string|null $platform
 * @property string|null $comments
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CanceledOrder|null $canceled
 * @property-read CarClass|null $carClass
 * @property-read Client|null $client
 * @property-read CompletedOrder|null $completed
 * @property-read OrderCorporate|null $corporate
 * @property-read Driver|null $driver
 * @property-read Franchise $franchise
 * @property-read Collection|OrderInProcessRoad[] $in_process_roads
 * @property-read int|null $in_process_roads_count
 * @property-read OrderInitialData|null $initial_data
 * @property-read OrderMeet|null $meet
 * @property-read Collection|OrderOnWayRoad[] $on_way_roads
 * @property-read int|null $on_way_roads_count
 * @property-read OrderType|null $orderType
 * @property-read Collection|OrderShippedDriver[] $ordering_shipments
 * @property-read int|null $ordering_shipments_count
 * @property-read PaymentType|null $paymentType
 * @property-read PreOrder|null $preorder
 * @property-read PreOrder|null $schedule
 * @property-read OrderStageCord|null $stage
 * @property-read OrderStatus|null $status
 * @property-read SystemWorker|null $system_worker
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddressFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddressTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCarClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCarOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereFranchisee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereFromCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereToCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order withTrashed()
 * @method static Builder|Order withoutTrashed()
 * @mixin Eloquent
 * @property-read Model|Eloquent $customer
 * @property-read OrderCommon|null $common
 * @property-read Collection|Driver[] $commons_driver
 * @property-read int|null $commons_driver_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property int $show_cord
 * @property string|null $lat
 * @property string|null $lut
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereLut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShowCord($value)
 * @property-read Collection|OrderConversationTalk[] $conversation_talk
 * @property-read int|null $conversation_talk_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read OrderProcess|null $process
 * @property-read Car|null $completed_car
 * @property-read Driver|null $completed_driver
 * @property int|null $passenger_id
 * @property int|null $operator_id
 * @property-read int|null $assessment_count
 * @property-read OrderInProcessRoad|null $in_process_road
 * @property-read OrderOnWayRoad|null $on_way_road
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOperatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePassengerId($value)
 * @property-read SystemWorker|null $operator
 * @property-read OrderAttach|null $attach
 * @property-read Collection|Complaint[] $complaints
 * @property-read int|null $complaints_count
 * @property-read OrderShippedDriver|null $current_shipped
 * @property-read Collection|OrderFeedback[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read Collection|OrderWorkerComment[] $worker_comments
 * @property-read int|null $worker_comments_count
 * @property-read HigherOrderBuilderProxy|mixed|string $from_short
 * @property-read HigherOrderBuilderProxy|mixed|string $to_short
 * @property-read Collection|OrderFeedback[] $assessment
 * @property-read Client|null $passenger
 * @property-read FranchiseTransaction|null $transaction_reason
 * @property-read FranchiseTransaction|null $reason
 * @property-read int|null $attach_count
 * @property-read Collection|CompletedOrderChange[] $changes
 * @property-read int|null $changes_count
 * @property-read Collection|OrderFeedbackComment[] $feedback_comments
 * @property-read int|null $feedback_comments_count
 * @property-read OrderShippedDriver|null $selected_shipped
 * @property-read CompletedOrderCrossing|null $crossing
 * @property-read OrderRent|null $rent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @property int $dist_type
 * @property-read EstimatedRating|null $estimated_rating
 * @property-read Collection|EstimatedRating[] $estimated_ratings
 * @property-read int|null $estimated_ratings_count
 * @property-read OrderShippedDriver|null $shipped
 * @property-read Driver|null $shipped_driver
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDistType($value)
 * @property int|null $customer_zone_id
 * @property int|null $location_zone_id
 * @property-read Collection|\Src\Models\Order\OrderCommon[] $commons
 * @property-read int|null $commons_count
 * @property-read Timezone|null $customer_zone
 * @property-read Timezone|null $location_zone
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerZoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereLocationZoneId($value)
 * @property \datetime $repeated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRepeatedAt($value)
 */
class Order extends ServiceModel
{
    use SoftDeletes;

    protected const LATITUDE = 'lat';
    protected const LONGITUDE = 'lut';

    public const ORDER_STATUS_PENDING = 1;
    public const ORDER_STATUS_IN_PROCESS = 2;
    public const ORDER_STATUS_PAUSED = 3;
    public const ORDER_STATUS_COMPLETED = 4;
    public const ORDER_STATUS_CANCELED = 5;

    public const PAYMENT_TYPE_CASH = 1;
    public const PAYMENT_TYPE_PAY_COMPANY = 2;
    public const PAYMENT_TYPE_CREDIT = 3;

    public const ORDER_TYPE_CLIENT = 1;
    public const ORDER_TYPE_CLIENT_BY_COMPANY = 2;
    public const ORDER_TYPE_COMPANY_TO_CLIENT = 3;
    public const ORDER_TYPE_TO_OTHER = 4;

    protected static bool $kilometers = false;
    /**
     * @var string
     */
    protected $table = 'orders';
    /**
     * @var string
     */
    protected $primaryKey = 'order_id';
    /**
     * @var array
     */
    protected $casts = [
        'car_option' => 'json',
        'from_coordinates' => 'array',
        'to_coordinates' => 'array',
        'franchisee' => 'json',
        'order_id' => 'integer',
        'repeated_at' => 'datetime:Y-m-d H:i:s'
    ];
    /**
     * @var string[]
     */
    protected $attributes = ['car_option' => '{"ids": []}'];
    /**
     * @var array
     */
    protected $fillable = [
        'passenger_id',
        'franchisee',
        'client_id',
        'car_class_id',
        'order_type_id',
        'payment_type_id',
        'operator_id',
        'location_zone_id',
        'customer_zone_id',
        'customer_id',
        'customer_type',
        'status_id',
        'car_option',
        'dist_type',
        'from_coordinates',
        'to_coordinates',
        'address_from',
        'address_to',
        'platform',
        'comments',
        'show_cord',
        'lat',
        'lut',
        'repeated_at'
    ];
    /**
     * @var string
     */
    protected string $map = 'order';

    /**
     * @return Builder|\Illuminate\Database\Eloquent\Builder|Model|object|Order|null
     */
    public static function pendingOrders()
    {
        return (new self())::where('status_id', self::ORDER_STATUS_PENDING)->groupBy('')->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|Model|object|Order|null
     */
    public static function inProcess()
    {
        return (new self())::where('status_id', self::ORDER_STATUS_IN_PROCESS)->first();
    }

    /**
     * @return mixed
     */
    public static function onPaused()
    {
        return (new self())::where('status_id', self::ORDER_STATUS_PAUSED)->first();
    }

    /**
     * @return mixed
     */
    public static function completedOrders()
    {
        return (new self())::where('status_id', self::ORDER_STATUS_COMPLETED)->first();
    }

    /**
     * @return mixed
     */
    public static function inProcessOrders()
    {
        return (new self())::where('status_id', self::ORDER_STATUS_IN_PROCESS)->first();
    }

    /**
     * @return mixed
     */
    public static function canceledOrders()
    {
        return (new self())::where('status_id', self::ORDER_STATUS_CANCELED)->first();
    }

    /**
     * @return HasOne
     */
    public function meet(): HasOne
    {
        return $this->hasOne(OrderMeet::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return BelongsTo
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class, 'operator_id', 'system_worker_id');
    }

    /**
     * @return HasOneDeep
     */
    public function corporate_clients(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->client(), (new Client())->corporate());
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

    /**
     * @return BelongsTo
     */
    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'passenger_id', 'client_id');
    }

    /**
     * @return HasOne
     */
    public function current_shipped(): HasOne
    {
        return $this->hasOne(OrderShippedDriver::class, 'order_id', 'order_id')->where('current', '=', true);
    }

    /**
     * @return HasOne
     */
    public function selected_shipped(): HasOne
    {
        return $this->hasOne(OrderShippedDriver::class, 'order_id', 'order_id')
            ->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED);
    }

    /**
     * @return HasOne
     */
    public function shipped(): HasOne
    {
        return $this->hasOne(OrderShippedDriver::class, 'order_id', 'order_id')->latest();
    }

    /**
     * @return HasOneThrough
     */
    public function driver(): HasOneThrough
    {
        return $this->hasOneThrough(
            Driver::class,
            OrderShippedDriver::class,
            'order_id', // Foreign key on clients table...
            'driver_id', // Foreign key on history table...
            'order_id', // Local key on suppliers table...
            'driver_id' // Local key on clients table...
        )
//            ->where('order_shipped_drivers.current', '=', true)
            ->where('order_shipped_drivers.status_id', '=', OrderShippedStatus::PRE_ACCEPTED);
    }

    /**
     * @return HasOneThrough
     */
    public function shipped_driver(): HasOneThrough
    {
        return $this->hasOneThrough(
            Driver::class,
            OrderShippedDriver::class,
            'order_id', // Foreign key on clients table...
            'driver_id', // Foreign key on history table...
            'order_id', // Local key on suppliers table...
            'driver_id' // Local key on clients table...
        )
            ->where('order_shipped_drivers.current', '=', true);
    }

    /**
     * @return HasOneThrough
     */
    public function completed_driver(): HasOneThrough
    {
        return $this->hasOneThrough(
            Driver::class,
            CompletedOrder::class,
            'order_id', // Foreign key on clients table...
            'driver_id', // Foreign key on history table...
            'order_id', // Local key on suppliers table...
            'driver_id' // Local key on clients table...
        );
    }

    /**
     * @return HasOneThrough
     */
    public function completed_car(): HasOneThrough
    {
        return $this->hasOneThrough(
            Car::class,
            CompletedOrder::class,
            'order_id', // Foreign key on clients table...
            'car_id', // Foreign key on history table...
            'order_id', // Local key on suppliers table...
            'car_id' // Local key on clients table...
        );
    }

    /**
     * @return HasManyDeep
     */
    public function preorder_drivers(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->preorder(), (new PreOrder())->drivers());
    }

    /**
     * @return HasOne
     */
    public function preorder(): HasOne
    {
        return $this->hasOne(PreOrder::class, $this->primaryKey);
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'status_id', 'order_status_id');
    }

    /**
     * @return HasManyThrough
     */
    public function on_way_roads(): HasManyThrough
    {
        return $this->hasManyThrough(OrderOnWayRoad::class, OrderShippedDriver::class, $this->primaryKey, 'shipment_driver_id');
    }

    /**
     * @return HasOneThrough
     */
    public function on_way_road(): HasOneThrough
    {
        return $this
            ->hasOneThrough(OrderOnWayRoad::class, OrderShippedDriver::class, $this->primaryKey, 'shipment_driver_id')
            ->where('selected', '=', true);
    }

    /**
     * @return HasManyThrough
     */
    public function in_process_roads(): HasManyThrough
    {
        return $this->hasManyThrough(OrderInProcessRoad::class, OrderShippedDriver::class, $this->primaryKey, 'shipment_driver_id');
    }

    /**
     * @return HasOneThrough
     */
    public function in_process_road(): HasOneThrough
    {
        return $this
            ->hasOneThrough(OrderInProcessRoad::class, OrderShippedDriver::class, $this->primaryKey, 'shipment_driver_id')
            ->where('selected', '=', true);
    }

    /**
     * @return HasOneThrough
     */
    public function process(): HasOneThrough
    {
        return $this->hasOneThrough(OrderProcess::class, OrderShippedDriver::class, 'order_id', 'order_shipped_id');
    }

    /**
     * @return BelongsTo
     */
    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id', 'payment_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function carClass(): BelongsTo
    {
        return $this->belongsTo(CarClass::class, 'car_class_id', 'car_class_id');
    }

    /**
     * @return BelongsTo
     */
    public function orderType(): BelongsTo
    {
        return $this->belongsTo(OrderType::class, 'order_type_id', 'order_type_id');
    }

    /**
     * @return BelongsToJson
     */
    public function car_options(): BelongsToJson
    {
        return $this->belongsToJson(CarOption::class, 'car_option->ids');
    }

    /**
     * @return BelongsTo
     */
    public function franchise(): BelongsTo
    {
        return $this->belongsToJson(Franchise::class, 'franchisee->ids', 'franchise_id');
    }

    /**
     * @return HasMany
     */
    public function ordering_shipments(): HasMany
    {
        return $this->hasMany(OrderShippedDriver::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @param  null  $driver_id
     * @return HasOne
     */
    public function ordering_shipment($driver_id = null): HasOne
    {
        $relation = $this->hasOne(OrderShippedDriver::class, $this->primaryKey);

        return $driver_id ? $relation->where('driver_id', '=', $driver_id) : $relation;
    }

    /**
     * @return MorphTo
     * @link WorkerOperator
     * @link WorkerDispatcher
     * @link Client
     * @link AdminCorporate
     */
    public function customer(): MorphTo
    {
        return $this->morphTo('customer', 'customer_type', 'customer_id');
    }

    /**
     * @return HasOneDeep
     */
    public function tariff(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->initial_data(), (new OrderInitialData())->initial_tariff());
    }

    /**
     * @return HasOne
     */
    public function initial_data(): HasOne
    {
        return $this->hasOne(OrderInitialData::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return HasOne
     */
    public function stage(): HasOne
    {
        return $this->hasOne(OrderStageCord::class, $this->primaryKey);
    }

    /**
     * @return HasMany
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'order_id', 'order_id');
    }

    /**
     * @return HasMany
     */
    public function feedbacks(): HasMany
    {
        return $this->hasMany(OrderFeedback::class, 'order_id', 'order_id');
    }

    /**
     * @return HasManyDeep
     */
    public function canceled_feedbacks(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->canceled(), (new CanceledOrder())->feedbacks());
    }

    /**
     * @return HasOne
     */
    public function canceled(): HasOne
    {
        return $this->hasOne(CanceledOrder::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return HasManyDeep
     */
    public function completed_feedbacks(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->completed(), (new CompletedOrder())->feedbacks());
    }

    /**
     * @return HasOne
     */
    public function completed(): HasOne
    {
        return $this->hasOne(CompletedOrder::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return HasMany
     */
    public function assessment(): HasMany
    {
        return $this->hasMany(OrderFeedback::class, $this->primaryKey)->where('assessment', '!=', 0);
    }

    /**
     * @return HasOneDeep
     */
    public function company(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->corporate(), (new OrderCorporate())->company());
    }

    /**
     * @return HasOne
     */
    public function corporate(): HasOne
    {
        return $this->hasOne(OrderCorporate::class, 'order_id', 'order_id');
    }

    /**
     * @return HasOne
     */
    public function common(): HasOne
    {
        return $this->hasOne(OrderCommon::class, $this->primaryKey, $this->primaryKey)->latest('order_common_id');
    }

    /**
     * @return HasMany
     */
    public function commons(): HasMany
    {
        return $this->hasMany(OrderCommon::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return HasMany
     */
    public function attach(): HasMany
    {
        return $this->hasMany(OrderAttach::class, $this->primaryKey);
    }

    /**
     * @return BelongsToMany
     */
    public function commons_driver(): BelongsToMany
    {
        return $this->belongsToMany(Driver::class, 'orders_common');
    }

    /**
     * @return BelongsToMany
     */
    public function conversation_talk(): BelongsToMany
    {
        return $this->belongsToMany(OrderConversationTalk::class, 'order_conversations', $this->primaryKey);
    }

    /**
     * @return HasMany
     */
    public function worker_comments(): HasMany
    {
        return $this->hasMany(OrderWorkerComment::class, 'order_id', 'order_id');
    }

    /**
     * @return MorphOne
     */
    public function reason(): MorphOne
    {
        return $this->morphOne(FranchiseTransaction::class, 'reason', 'reason_type', 'reason_id');
    }

    /**
     * @return HasManyThrough
     */
    public function changes(): HasManyThrough
    {
        return $this->hasManyThrough(CompletedOrderChange::class, CompletedOrder::class, 'completed_id', 'order_id');
    }

    /**
     * @return HasManyThrough
     */
    public function feedback_comments(): HasManyThrough
    {
        return $this->hasManyThrough(OrderFeedbackComment::class, OrderFeedback::class, 'order_feedback_id', 'order_id');
    }

    /**
     * @return HasOneThrough
     */
    public function crossing(): HasOneThrough
    {
        return $this->hasOneThrough(CompletedOrderCrossing::class, CompletedOrder::class, 'order_id', 'completed_id', 'order_id', 'completed_order_id');
    }

    /**
     * @return HasOne
     */
    public function rent(): HasOne
    {
        return $this->hasOne(OrderRent::class, 'order_id', 'order_id');
    }

    /**
     * @return HasMany
     */
    public function estimated_ratings(): HasMany
    {
        return $this->hasMany(EstimatedRating::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return HasOne
     */
    public function estimated_rating(): HasOne
    {
        return $this->hasOne(EstimatedRating::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return BelongsTo
     */
    public function customer_zone(): BelongsTo
    {
        return $this->belongsTo(Timezone::class, 'customer_zone_id', 'timezone_id');
    }

    /**
     * @return BelongsTo
     */
    public function location_zone(): BelongsTo
    {
        return $this->belongsTo(Timezone::class, 'location_zone_id', 'timezone_id');
    }

    /*==================================================GETTER SETTER==============================================*/
    /**
     * @param $value
     * @return HigherOrderBuilderProxy|mixed|string
     */
    public function getToShortAttribute($value)
    {
        return Address::where('address', '=', $value)->first(['address', 'short_address'])->short_address ?? '';
    }

    /**
     * @param $value
     * @return HigherOrderBuilderProxy|mixed|string
     */
    public function getFromShortAttribute($value)
    {
        return Address::where('address', '=', $value)->first(['address', 'short_address'])->short_address ?? '';
    }
    /*==================================================GETTER SETTER==============================================*/
}
