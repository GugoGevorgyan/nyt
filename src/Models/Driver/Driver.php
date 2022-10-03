<?php

declare(strict_types=1);

namespace Src\Models\Driver;

use Eloquent;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceAuthenticable;
use Src\Models\Car\Car;
use Src\Models\Car\CarClass;
use Src\Models\Car\CarCrash;
use Src\Models\Car\CarOption;
use Src\Models\CarReport\CarReport;
use Src\Models\Chat\OrderConversation;
use Src\Models\Chat\OrderConversationTalk;
use Src\Models\Client\Client;
use Src\Models\Client\ClientFavoriteDriver;
use Src\Models\Client\ClientTaxiView;
use Src\Models\Debt\Debt;
use Src\Models\Franchise\Franchise;
use Src\Models\Franchise\FranchiseTransaction;
use Src\Models\LegalEntity\LegalEntity;
use Src\Models\Oauth\FcmClient;
use Src\Models\Oauth\Token;
use Src\Models\Order\CanceledOrder;
use Src\Models\Order\CompletedOrder;
use Src\Models\Order\Order;
use Src\Models\Order\OrderCommon;
use Src\Models\Order\OrderCorporate;
use Src\Models\Order\OrderFeedback;
use Src\Models\Order\OrderFeedbackOption;
use Src\Models\Order\OrderInProcessRoad;
use Src\Models\Order\OrderOnWayRoad;
use Src\Models\Order\OrderProcess;
use Src\Models\Order\OrderShippedDriver;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\PreOrder;
use Src\Models\Park;
use Src\Models\RatingSystem\EstimatedRating;
use Src\Models\Role\Permission;
use Src\Models\Role\Role;
use Src\Models\Terminal\Terminal;
use Src\Models\Terminal\Waybill;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;
use Staudenmeir\EloquentJsonRelations\Relations\HasManyJson;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class Driver
 *
 * @package Src\Models\Driver
 * @property int $driver_id
 * @property int|null $driver_info_id
 * @property int|null $entity_id
 * @property int|null $car_id
 * @property int|null $type_id
 * @property int|null $subtype_id
 * @property int|null $graphic_id
 * @property int|null $free_days_price
 * @property int|null $busy_days_price
 * @property int|null $current_franchise_id
 * @property int|null $current_status_id
 * @property int $rating_level_id
 * @property mixed|null $options
 * @property float|null $lat
 * @property float|null $lut
 * @property float $mean_assessment
 * @property float $rating
 * @property int|null $logged
 * @property string|null $device
 * @property int $online
 * @property int $is_ready
 * @property string|null $nickname
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $password
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|DriverAddress[] $addresses
 * @property-read int|null $addresses_count
 * @property-read int|null $assessmentables_count
 * @property-read int|null $assessments_count
 * @property-read Collection|OrderFeedback[] $canceled_feedbacks
 * @property-read int|null $canceled_feedbacks_count
 * @property-read Collection|CanceledOrder[] $canceled_orders
 * @property-read int|null $canceled_orders_count
 * @property-read Car|null $car
 * @property-read Collection|\Src\Models\Oauth\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read int|null $clients_road_taxi_view_count
 * @property-read Collection|Order[] $common
 * @property-read int|null $common_count
 * @property-read Collection|OrderFeedback[] $completed_feedbacks
 * @property-read int|null $completed_feedbacks_count
 * @property-read Collection|DriverContract[] $contracts
 * @property-read int|null $contracts_count
 * @property-read Collection|DriverCoordinate[] $coordinates
 * @property-read int|null $coordinates_count
 * @property-read DriverCoordinate|null $current_coordinates
 * @property-read Franchise|null $current_franchise
 * @property-read DriverInfo|null $driver_info
 * @property-read LegalEntity|null $entity
 * @property-read EstimatedRating|null $estimated_rating
 * @property-read Collection|EstimatedRating[] $estimated_ratings
 * @property-read int|null $estimated_ratings_count
 * @property-read Collection|Client[] $favorite
 * @property-read int|null $favorite_count
 * @property-read Collection|Franchise[] $franchisee
 * @property-read int|null $franchisee_count
 * @property-read float|int $distance
 * @property-read DriverGraphic|null $graphic
 * @property-read DriverAddress|null $home_address
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Token|null $oauth_access_token
 * @property-read Collection|Order[] $current_order
 * @property-read OrderOnWayRoad|null $order_on_way_road
 * @property-read Collection|OrderOnWayRoad[] $order_on_way_roads
 * @property-read int|null $order_on_way_roads_count
 * @property-read Collection|CompletedOrder[] $orders
 * @property-read int|null $orders_count
 * @property-read Park|null $park
 * @property-read DriverRatingLevel $rating_level
 * @property-read int|null $ratings_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read int|null $scheduled_count
 * @property-read Collection|DriverSchedule[] $schedules
 * @property-read int|null $schedules_count
 * @property-read DriverStatus|null $status
 * @property-read DriverSubtype|null $subtype
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read DriverType|null $type
 * @property-read Collection|DriverAddress[] $work_addresses
 * @property-read int|null $work_addresses_count
 * @method static Builder|Driver canceledOrderFeedback()
 * @method static Builder|Driver completedOrderFeedback()
 * @method static Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Driver newModelQuery()
 * @method static Builder|Driver newQuery()
 * @method static \Illuminate\Database\Query\Builder|Driver onlyTrashed()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|Driver query()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|Driver whereBusyDaysPrice($value)
 * @method static Builder|Driver whereCarId($value)
 * @method static Builder|Driver whereCreatedAt($value)
 * @method static Builder|Driver whereCurrentFranchiseId($value)
 * @method static Builder|Driver whereCurrentStatusId($value)
 * @method static Builder|Driver whereDeletedAt($value)
 * @method static Builder|Driver whereDevice($value)
 * @method static Builder|Driver whereDriverId($value)
 * @method static Builder|Driver whereDriverInfoId($value)
 * @method static Builder|Driver whereEmail($value)
 * @method static Builder|Driver whereEntityId($value)
 * @method static Builder|Driver whereFreeDaysPrice($value)
 * @method static Builder|Driver whereGraphicId($value)
 * @method static Builder|Driver whereIsReady($value)
 * @method static Builder|Driver whereLat($value)
 * @method static Builder|Driver whereLogged($value)
 * @method static Builder|Driver whereLut($value)
 * @method static Builder|Driver whereMeanAssessment($value)
 * @method static Builder|Driver whereNickname($value)
 * @method static Builder|Driver whereOnline($value)
 * @method static Builder|Driver whereOptions($value)
 * @method static Builder|Driver wherePassword($value)
 * @method static Builder|Driver wherePhone($value)
 * @method static Builder|Driver whereRating($value)
 * @method static Builder|Driver whereRatingLevelId($value)
 * @method static Builder|Driver whereSubtypeId($value)
 * @method static Builder|Driver whereTypeId($value)
 * @method static Builder|Driver whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Driver withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Driver withoutTrashed()
 * @mixin Eloquent
 * @property-read OrderShippedDriver|null $active_order_shipment
 * @property int|null $azimuth
 * @property-read DriverContract|null $active_contract
 * @property-read Collection|OrderConversationTalk[] $conversation
 * @property-read int|null $conversation_count
 * @property-read Collection|OrderConversationTalk[] $conversation_sender
 * @property-read int|null $conversation_sender_count
 * @method static Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|Driver whereAzimuth($value)
 * @property-read Collection|OrderConversationTalk[] $conversation_talks
 * @property-read int|null $conversation_talks_count
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property array|null $selected_option
 * @property CarClass $selected_class
 * @property-read OrderProcess|null $all_process
 * @property-read OrderProcess|null $process
 * @property-read CarOption $selected_options
 * @property-read Collection|Waybill[] $waybills
 * @property-read int|null $waybills_count
 * @method static Builder|Driver whereSelectedClass($value)
 * @method static Builder|Driver whereSelectedOption($value)
 * @property-read DriverWallet|null $cash
 * @property-read Terminal|null $terminal
 * @property-read Collection|OrderFeedback[] $assessed
 * @property-read int|null $assessed_count
 * @property-read Collection|CarCrash[] $crashes
 * @property-read int|null $crashes_count
 * @property-read Collection|OrderFeedback[] $rater
 * @property-read int|null $rater_count
 * @property-read Collection|CarReport[] $report
 * @property-read int|null $report_count
 * @property-read DriverContract|null $unsigned_contract
 * @property-read DriverCoordinate|null $current_coordinate
 * @property-read FcmClient|null $fcm
 * @property-read Collection|PreOrder[] $preorders
 * @property-read int|null $preorders_count
 * @property-read Collection|Client[] $favorite_client
 * @property-read int|null $favorite_client_count
 * @property-read DriverLock|null $lock
 * @property-read DriverLock|null $locked
 * @property-read Collection|FranchiseTransaction[] $transactions
 * @property-read int|null $transactions_count
 * @property-read Waybill|null $current_waybill
 * @property-read Collection|Debt[] $debts
 * @property-read int|null $debts_count
 * @property-read Waybill|null $last_active_waybill
 * @property-read Collection|FranchiseTransaction[] $side
 * @property-read int|null $side_count
 * @property-read CompletedOrder|null $completed_order
 * @property-read Collection|Waybill[] $last_active_waybills
 * @property-read int|null $last_active_waybills_count
 * @property-read Collection|Waybill[] $active_waybills
 * @property-read int|null $active_waybills_count
 * @property-read Collection|FranchiseTransaction[] $last_side
 * @property-read int|null $last_side_count
 * @method static Builder|ServiceAuthenticable cordDistance($latitude, $longitude)
 * @property-read Collection|CompletedOrder[] $completed_orders
 * @property-read int|null $completed_orders_count
 * @property-read Collection|OrderCorporate[] $corporate_orders
 * @property-read int|null $corporate_orders_count
 * @property-read CompletedOrder|null $last_completed_order
 * @property-read OrderCorporate|null $last_corporate_order
 * @property-read DriverLock|null $lockes
 * @property-read Waybill|null $current_active_waybill
 * @property-read Collection|Debt[] $to_debts
 * @property-read int|null $to_debts_count
 * @property-read Collection|OrderCommon[] $common_orders
 * @property-read int|null $common_orders_count
 * @property-read Collection|OrderCommon[] $common_preorders
 * @property-read int|null $common_preorders_count
 * @property-read OrderInProcessRoad|null $order_in_process_road
 * @property-read Collection|OrderInProcessRoad[] $order_in_process_roads
 * @property-read int|null $order_in_process_roads_count
 */
class Driver extends ServiceAuthenticable
{
    use SoftDeletes;

    public const LICENSE_TYPE_A = 'A';
    public const LICENSE_TYPE_A1 = 'A1';
    public const LICENSE_TYPE_B = 'B';
    public const LICENSE_TYPE_B1 = 'B1';
    public const LICENSE_TYPE_C = 'C';
    public const LICENSE_TYPE_C1 = 'C1';
    public const LICENSE_TYPE_D = 'D';
    public const LICENSE_TYPE_D1 = 'D1';
    public const LICENSE_TYPE_BE = 'BE';
    public const LICENSE_TYPE_CE = 'CE';
    public const LICENSE_TYPE_CE1 = 'C1E';
    public const LICENSE_TYPE_DE = 'D1E';
    public const CURRENT_STATUS_IS_FREE = 1;
    public const CURRENT_STATUS_IN_ORDER = 2;
    public const CURRENT_STATUS_GO_ORDER = 3;

    protected const LATITUDE = 'lat';
    protected const LONGITUDE = 'lut';
    protected static bool $kilometers = false;

    /**
     * @var string[]
     */
    public $socketAuth = ['driver_id', 'phone', 'car_id', 'current_franchise_id'];
    /**
     * @var string
     */
    public $username = 'nickname';
    /**
     * @var string
     */
    protected $guard_name = 'drivers_api';
    /**
     * @var string
     */
    protected $guard = 'drivers_api';
    /**
     * @var string
     */
    protected $table = 'drivers';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_id';
    /**
     * @var array
     */
    protected $casts = [
        'lat' => 'float',
        'lut' => 'float',
        'distance' => 'float',
        'selected_class' => 'json',
        'selected_option' => 'json',
        'is_ready' => 'bool',
        'logged' => 'bool',
        'online' => 'bool',
        'mean_assessment' => 'double'
    ];
    /**
     * @var string[]
     */
    protected $attributes = ['selected_option' => '{"ids": []}', 'selected_class' => '{"ids": []}'];
    /**
     * @var array
     */
    protected $hidden = ['password'];
    /**
     * @var array
     */
    protected $fillable = [
        'driver_id',
        'entity_id',
        'driver_info_id',
        'current_status_id',
        'current_franchise_id',
        'selected_class',
        'selected_option',
        'mean_assessment',
        'online',
        'rating',
        'logged',
        'is_ready',
        'lat',
        'lut',
        'azimuth',
        'nickname',
        'device',
        'password',
        'phone',
        'car_id',
    ];
    /**
     * @var string
     */
    protected string $map = 'driver';

    /**
     * @param $phone
     */
    public function setPhoneAttribute($phone): void
    {
        $this->attributes['phone'] = preg_replace('/[\D]/', '', (string)$phone);
    }

    /**
     * @param $password
     */
    public function setPasswordAttribute($password): void
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(LegalEntity::class, 'entity_id', 'legal_entity_id');
    }

    /**
     * @return HasOne
     */
    public function oauth_access_token(): HasOne
    {
        return $this->hasOne(Token::class, 'user_id', 'driver_id');
    }

    /**
     * @param $username
     * @return Builder|Model|object|Driver|null
     */
    public function findForPassport($username)
    {
        return self::where($this->username, '=', $username)->first();
    }

    /**
     * @param $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password): bool
    {
        if ($this->redis()->get("oauth_password$password") === $password) {
            $this->redis()->del("oauth_password$password");
            return true;
        }

        return Hash::check($password, $this->password);
    }

    /**
     * @return HasOneDeep
     */
    public function car_class(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->car(), (new Car())->classes());
    }

    /**
     * @return BelongsTo
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    /**
     * @return HasMany
     */
    public function crashes(): HasMany
    {
        return $this->hasMany(CarCrash::class, 'driver_id', 'driver_id');
    }

    /**
     * @return HasOneDeep
     */
    public function type(): HasOneDeep
    {
        return $this
            ->hasOneDeepFromRelations($this->active_contract(), (new DriverContract())->type())
            ->where('active', '=', true);
    }

    /**
     * @return HasOne
     */
    public function active_contract(): HasOne
    {
        return $this->hasOne(DriverContract::class, 'driver_id')->where('active', '=', 1);
    }

    /**
     * @return HasOneDeep
     */
    public function subtype(): HasOneDeep
    {
        return $this
            ->hasOneDeepFromRelations($this->active_contract(), (new DriverContract())->subtype())
            ->where('active', '=', true);
    }

    /**
     * @return HasOneDeep
     */
    public function graphic(): HasOneDeep
    {
        return $this
            ->hasOneDeepFromRelations($this->active_contract(), (new DriverContract())->graphic())
            ->where('active', '=', true);
    }

    /**
     * @return HasMany
     */
    public function coordinates(): HasMany
    {
        return $this->hasMany(DriverCoordinate::class, $this->primaryKey);
    }

    /**
     * @return HasOne
     */
    public function current_coordinate(): HasOne
    {
        return $this->hasOne(DriverCoordinate::class, $this->primaryKey)->latest();
    }

    /**
     * @return BelongsTo
     */
    public function driver_info(): BelongsTo
    {
        return $this->belongsTo(DriverInfo::class, 'driver_info_id');
    }

    /**
     * @return BelongsToMany
     */
    public function franchisee(): BelongsToMany
    {
        return $this
            ->belongsToMany(Franchise::class, 'franchise_driver', 'driver_id', 'franchise_id')
            ->withPivot(['type_id', 'graphic_id'])
            ->using(FranchiseDriver::class);
    }

    /**
     * @return belongsTo
     */
    public function current_franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class, 'current_franchise_id', 'franchise_id', 'franchise_id');
    }

    /**
     * @return HasMany
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(DriverContract::class, 'driver_id', 'driver_id')->where('signed', '=', 1);
    }

    /**
     * @return HasOne
     */
    public function unsigned_contract(): HasOne
    {
        return $this->hasOne(DriverContract::class, 'driver_id')
            ->where('active', '=', 0)
            ->where('signed', '=', 0)
            ->latest();
    }

    /**
     * @return BelongsToJson
     */
    public function options(): BelongsToJson
    {
        return $this->belongsToJson(DriverTypeOptional::class, 'selected_option->id');
    }

    /**
     * @return HasOneThrough
     */
    public function park(): HasOneThrough
    {
        return $this->hasOneThrough(Park::class, Car::class, 'car_id', 'park_id', 'car_id', 'park_id');
    }

    /**
     * @return HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(DriverSchedule::class, 'driver_id');
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(DriverStatus::class, 'current_status_id');
    }

    /**
     * @return MorphMany
     */
    public function completed_feedbacks(): MorphMany
    {
        return $this->morphMany(OrderFeedback::class, 'writable')->whereHasMorph('orderable', CompletedOrder::class);
    }

    /**
     * @return MorphMany
     */
    public function canceled_feedbacks(): MorphMany
    {
        return $this->morphMany(OrderFeedback::class, 'writable')->whereHasMorph('orderable', CanceledOrder::class);
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(CompletedOrder::class, 'driver_id', 'driver_id');
    }

    /**
     * @return HasMany
     */
    public function completed_orders(): HasMany
    {
        return $this->hasMany(CompletedOrder::class, 'driver_id', 'driver_id');
    }

    /**
     * @return HasOne
     */
    public function last_completed_order(): HasOne
    {
        return $this->hasOne(CompletedOrder::class, 'driver_id', 'driver_id')
            ->latest('created_at');
    }

    /**
     * @return HasMany
     */
    public function corporate_orders(): HasMany
    {
        return $this->hasMany(OrderCorporate::class, 'driver_id', 'driver_id');
    }

    /**
     * @return HasOne
     */
    public function last_corporate_order(): HasOne
    {
        return $this->hasOne(OrderCorporate::class, 'driver_id', 'driver_id')
            ->latest('created_at');
    }

    /**
     * @return BelongsTo
     */
    public function rating_level(): BelongsTo
    {
        return $this->belongsTo(DriverRatingLevel::class, 'rating_level_id');
    }

    /**
     * @return BelongsToMany
     */
    public function favorite_client(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'client_favorite_driver', 'driver_id', 'client_id');
    }

    /**
     * @return HasMany
     */
    public function favorite(): HasMany
    {
        return $this->hasMany(ClientFavoriteDriver::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return HasMany
     */
    public function estimated_ratings(): HasMany
    {
        return $this->hasMany(EstimatedRating::class, 'driver_id');
    }

    /**
     * @return HasOne
     */
    public function estimated_rating(): HasOne
    {
        return $this->hasOne(EstimatedRating::class, 'driver_id');
    }

    ///////////////////////////////////////////REAL TIME DATA/////////////////////////////////////

    /**
     * @return HasManyJson
     */
    public function client_driver_view(): HasManyJson
    {
        return $this->hasManyJson(ClientTaxiView::class, 'driver->ids');
    }

    /**
     * @return HasOneDeep
     */
    public function order_client(): HasOneDeep
    {
        $table_name = 'order_shipped_drivers';

        return $this->hasOneDeepFromRelations($this->current_order(), (new Order())->client())
            ->where("$table_name.current", '=', 1)
            ->where("$table_name.status_id", '=', OrderShippedStatus::PRE_ACCEPTED);
    }

    /**
     * @return HasOneDeep
     */
    public function current_order(): HasOneDeep
    {
        $table_name = 'order_shipped_drivers';

        return $this->hasOneDeepFromRelations($this->current_order_shipment(), (new OrderShippedDriver())->order())
            ->where("$table_name.current", '=', 1)
            ->where("$table_name.status_id", '=', OrderShippedStatus::PRE_ACCEPTED);
    }

    /**
     * @param  int|null  $order_id
     * @return HasOne
     */
    public function current_order_shipment(int $order_id = null): HasOne
    {
        $table_name = 'order_shipped_drivers';

        $relation = $this->hasOne(OrderShippedDriver::class, $this->primaryKey)
            ->where("$table_name.current", '=', true)
            ->where("$table_name.status_id", '=', OrderShippedStatus::PRE_ACCEPTED);

        return $order_id ? $relation->where('order_id', '=', $order_id) : $relation;
    }

    /**
     * @return HasManyDeep
     */
    public function current_order_stages(): HasManyDeep
    {
        $table_name = 'order_shipped_drivers';

        return $this->hasOneDeepFromRelations($this->current_order_shipment(), (new OrderShippedDriver())->order_stages())
            ->where("$table_name.current", '=', 1)
            ->where("$table_name.status_id", '=', OrderShippedStatus::PRE_ACCEPTED);
    }

    /**
     * @return HasOne
     */
    public function active_order_shipment(): HasOne
    {
        return $this->hasOne(OrderShippedDriver::class, $this->primaryKey)
            ->where('current', '=', 1);
    }

    /**
     * @return HasOneDeep
     */
    public function initial_order_data(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->current_order(), (new Order())->initial_data());
    }

    /**
     * @param  int|null  $order_id
     * @return HasMany
     */
    public function orders_shipment(int $order_id = null): HasMany
    {
        $relation = $this->hasMany(OrderShippedDriver::class, $this->primaryKey, $this->primaryKey);

        return $order_id ? $relation->where('order_id', $order_id) : $relation;
    }

    /**
     * @param  int|null  $order_id
     * @return HasOne
     */
    public function order_shipment(int $order_id = null): HasOne
    {
        $relation = $this->hasOne(OrderShippedDriver::class, $this->primaryKey);

        return $order_id ? $relation->where('order_id', $order_id) : $relation;
    }

    /**
     * @return MorphMany
     */
    public function canceled_orders(): MorphMany
    {
        return $this->morphMany(CanceledOrder::class, 'cancelable');
    }

    /**
     * You Assessments
     * @return MorphMany
     */
    public function rater(): MorphMany
    {
        return $this->morphMany(OrderFeedback::class, 'writable')->where('assessment', '!=', 0);
    }

    /**
     * Your Assessments
     * @return MorphMany
     */
    public function assessed(): MorphMany
    {
        return $this->morphMany(OrderFeedback::class, 'readable')->where('assessment', '!=', 0);
    }

    /**
     * @return HasOneThrough
     */
    public function process(): HasOneThrough
    {
        return $this
            ->hasOneThrough(OrderProcess::class, OrderShippedDriver::class, 'driver_id', 'order_shipped_id')
            ->where('current', '=', 1)
            ->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED);
    }

    /**
     * @return HasOneThrough
     */
    public function all_process(): HasOneThrough
    {
        return $this
            ->hasOneThrough(OrderProcess::class, OrderShippedDriver::class, 'driver_id', 'order_shipped_id');
    }

    /**
     * @return HasManyThrough
     */
    public function order_on_way_roads(): HasManyThrough
    {
        return $this->hasManyThrough(OrderOnWayRoad::class, OrderShippedDriver::class, 'driver_id', 'shipment_driver_id');
    }

    /**
     * @return HasOneThrough
     */
    public function order_on_way_road(): HasOneThrough
    {
        return $this->hasOneThrough(OrderOnWayRoad::class, OrderShippedDriver::class, 'driver_id', 'shipment_driver_id')
            ->where('order_shipped_drivers.current', '=', true)
            ->where('order_on_way_roads.selected', '=', true);
    }

    /**
     * @return HasManyThrough
     */
    public function order_in_process_roads(): HasManyThrough
    {
        return $this->hasManyThrough(OrderInProcessRoad::class, OrderShippedDriver::class, 'driver_id', 'shipment_driver_id');
    }

    /**
     * @return HasOneThrough
     */
    public function order_in_process_road(): HasOneThrough
    {
        return $this->hasOneThrough(OrderInProcessRoad::class, OrderShippedDriver::class, 'driver_id', 'shipment_driver_id')
            ->where('order_shipped_drivers.current', '=', true)
            ->where('order_in_process_roads.selected', '=', true);
    }

    /**
     * @return HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(DriverAddress::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return HasOne
     */
    public function home_address(): HasOne
    {
        return $this->hasOne(DriverAddress::class)->where('target', '=', 'HOME');
    }

    /**
     * @return HasMany
     */
    public function work_addresses(): HasMany
    {
        return $this->hasMany(DriverAddress::class)->where('target', '=', 'WORK');
    }

    /**
     * @return HasMany
     */
    public function common_preorders(): HasMany
    {
        return $this->hasManyJson(OrderCommon::class, 'driver->ids', $this->primaryKey)->has('preorder');
    }

    /**
     * @return HasMany
     */
    public function common_orders(): HasMany
    {
        return $this->hasManyJson(OrderCommon::class, 'driver->ids', $this->primaryKey);
    }

    /**
     * @return HasManyJson
     */
    public function active_commons(): HasManyJson
    {
        return $this->hasManyJson(OrderCommon::class, 'driver->ids', $this->primaryKey)->where('accept', '=', true);
    }

    /**
     * @return HasMany
     */
    public function conversation(): HasMany
    {
        return $this->hasMany(OrderConversation::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return BelongsToMany
     */
    public function conversation_talks(): BelongsToMany
    {
        return $this->belongsToMany(OrderConversationTalk::class, 'order_conversations', $this->primaryKey);
    }

    /**
     * @return MorphMany
     */
    public function conversation_sender(): MorphMany
    {
        return $this->morphMany(OrderConversationTalk::class, 'sender', 'sender_type', 'sender_id');
    }

    /**
     * @return BelongsTo
     */
    public function selected_class(): BelongsTo
    {
        return $this->belongsToJson(CarClass::class, 'selected_class->ids');
    }

    /**
     * @return BelongsTo
     */
    public function selected_options(): BelongsTo
    {
        return $this->belongsToJson(CarOption::class, 'selected_option->ids');
    }

    /**
     * @return HasMany
     */
    public function waybills(): HasMany
    {
        return $this->hasMany(Waybill::class, $this->primaryKey);
    }

    /**
     * @return HasOne
     */
    public function current_waybill(): HasOne
    {
        return $this->hasOne(Waybill::class, $this->primaryKey)
            ->withTrashed()
            ->whereDate('start_time', '<=', Carbon::now())
            ->whereDate('end_time', '>', Carbon::now())
            ->latest();
    }

    /**
     * @return HasOne
     */
    public function last_active_waybill(): HasOne
    {
        return $this->hasOne(Waybill::class, $this->primaryKey)
            ->latest();
    }

    /**
     * @return HasMany
     */
    public function active_waybills(): HasMany
    {
        return $this->hasMany(Waybill::class, $this->primaryKey)
            ->whereDate('end_time', '>', Carbon::now());
    }

    /**
     * @return HasOne
     */
    public function current_active_waybill(): HasOne
    {
        return $this->hasOne(Waybill::class, $this->primaryKey, $this->primaryKey)
            ->where('start_time', '<=', f_now())
            ->where('end_time', '>=', f_now())
            ->where('verified', '=', true)
            ->where('signed', '=', true);
    }

    /**
     * @return HasOne
     */
    public function cash(): HasOne
    {
        return $this->hasOne(DriverWallet::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return HasOne
     */
    public function terminal(): HasOne
    {
        return $this->hasOne(Terminal::class, 'auth_driver_id', $this->primaryKey);
    }

    /**
     * @return HasManyThrough
     */
    public function report(): HasManyThrough
    {
        return $this->hasManyThrough(CarReport::class, Waybill::class, 'driver_id', 'waybill_id');
    }

    /**
     * @return HasOne
     */
    public function lockes(): HasOne
    {
        return $this->hasOne(DriverLock::class, $this->primaryKey);
    }

    /**
     * @return HasOne
     */
    public function locked(): HasOne
    {
        return $this->hasOne(DriverLock::class, $this->primaryKey)->where('locked', '=', true);
    }

    /**
     * @return MorphOne
     */
    public function fcm(): MorphOne
    {
        return $this->morphOne(FcmClient::class, 'client', 'client_type', 'client_id');
    }

    /**
     * @return MorphMany
     */
    public function side(): MorphMany
    {
        return $this->morphMany(FranchiseTransaction::class, 'side', 'side_type', 'side_id');
    }

    /**
     * @return MorphMany
     */
    public function last_side(): MorphMany
    {
        return $this->morphMany(FranchiseTransaction::class, 'side', 'side_type', 'side_id')
            ->latest('created_at');
    }

    /**
     * @return MorphMany
     */
    public function to_debts(): MorphMany
    {
        return $this->morphMany(Debt::class, 'current_debt', 'debtor_type', 'debtor_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function candidate(): BelongsToThrough
    {
        return $this->belongsToThrough(DriverCandidate::class, DriverInfo::class, null, '',
            [DriverCandidate::class => 'driver_info_id', DriverInfo::class => 'driver_info_id']
        );
    }

    ///////////////////////////////////////////////////////////////SCOPES/////////////////////////////////////////////////

    /**
     * @return Builder
     */
    public function scopeCompletedOrderFeedback(): Builder
    {
        return (new OrderFeedbackOption())
            ::where('owner_type', '=', self::class)
            ->where('completed', '=', true)
            ->where('canceled', '=', false);
    }

    /**
     * @return Builder
     */
    public function scopeCanceledOrderFeedback(): Builder
    {
        return (new OrderFeedbackOption())
            ::where('owner_type', '=', self::class)
            ->where('canceled', '=', true)
            ->where('completed', '=', false);
    }

    ////////////////////////////////////////////////////////////END SCOPES////////////////////////////////////////////////
}
