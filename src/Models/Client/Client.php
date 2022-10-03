<?php

declare(strict_types=1);

namespace Src\Models\Client;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
use Illuminate\Support\Facades\Hash;
use ServiceEntity\Models\ServiceAuthenticable;
use Src\Models\Chat\OrderConversation;
use Src\Models\Chat\OrderConversationTalk;
use Src\Models\Corporate\CorporateClient;
use Src\Models\Driver\Driver;
use Src\Models\Franchise\FranchiseTransaction;
use Src\Models\Oauth\FcmClient;
use Src\Models\Oauth\Token;
use Src\Models\Order\CanceledOrder;
use Src\Models\Order\CompletedOrder;
use Src\Models\Order\Order;
use Src\Models\Order\OrderFeedback;
use Src\Models\Order\OrderFeedbackOption;
use Src\Models\Order\OrderInitialData;
use Src\Models\Order\OrderInProcessRoad;
use Src\Models\Order\OrderOnWayRoad;
use Src\Models\Order\OrderProcess;
use Src\Models\Order\OrderShippedDriver;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\OrderStatus;
use Src\Models\Order\PreOrder;
use Src\Models\PayCards\PayCard;
use Src\Models\Role\Permission;
use Src\Models\Role\Role;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

/**
 * Class Client
 *
 * @package Src\Models\Client
 * @property int $client_id
 * @property string $phone
 * @property string|null $email
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $device
 * @property int|null $mean_assessment
 * @property int $logged
 * @property int $online
 * @property int $in_order
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|ClientAddress[] $addresses
 * @property-read int|null $addresses_count
 * @property-read Collection|ClientSessionInfo[] $all_session_info
 * @property-read int|null $all_session_info_count
 * @property-read BeforeAuthClient|null $before_auth
 * @property-read Collection|BeforeAuthClient[] $before_auths
 * @property-read int|null $before_auths_count
 * @property-read Collection|ClientCall[] $call
 * @property-read int|null $call_count
 * @property-read Order|null $cancel_order
 * @property-read Collection|OrderFeedback[] $canceled_feedbacks
 * @property-read int|null $canceled_feedbacks_count
 * @property-read Collection|CanceledOrder[] $canceled_orders
 * @property-read int|null $canceled_orders_count
 * @property-read Collection|\Src\Models\Oauth\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read Collection|OrderFeedback[] $completed_feedbacks
 * @property-read int|null $completed_feedbacks_count
 * @property-read Collection|CorporateClient[] $corporate
 * @property-read int|null $corporate_count
 * @property-read Collection|Order[] $corporateOrders
 * @property-read int|null $corporate_orders_count
 * @property-read CanceledOrder|null $current_canceled_order
 * @property-read Order $current_order
 * @property-read ClientTaxiView|null $drivers_view
 * @property-read Collection|ClientTaxiView[] $drivers_views
 * @property-read int|null $drivers_views_count
 * @property-read Collection|Driver[] $favoriteDrivers
 * @property-read int|null $favorite_drivers_count
 * @property-read OrderInitialData|null $initial_order_data
 * @property-read Collection|Order[] $last_orders
 * @property-read int|null $last_orders_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read ClientSessionInfo|null $session_info
 * @property-read ClientSetting|null $setting
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|Client canceledOrderFeedback()
 * @method static Builder|Client completedOrderFeedback()
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|Client newModelQuery()
 * @method static Builder|Client newQuery()
 * @method static \Illuminate\Database\Query\Builder|Client onlyTrashed()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|Client query()
 * @method static Builder|Client resetedOrderFeedback()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|Client whereClientId($value)
 * @method static Builder|Client whereCreatedAt($value)
 * @method static Builder|Client whereDeletedAt($value)
 * @method static Builder|Client whereDevice($value)
 * @method static Builder|Client whereEmail($value)
 * @method static Builder|Client whereInOrder($value)
 * @method static Builder|Client whereLogged($value)
 * @method static Builder|Client whereMeanAssessment($value)
 * @method static Builder|Client whereOnline($value)
 * @method static Builder|Client wherePassword($value)
 * @method static Builder|Client wherePhone($value)
 * @method static Builder|Client whereRememberToken($value)
 * @method static Builder|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Client withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Client withoutTrashed()
 * @mixin Eloquent
 * @property-read int|null $assessmentables_count
 * @property-read int|null $assessments_count
 * @property object initial_order
 * @property-read CompletedOrder|null $completed_order
 * @property-read Collection|CompletedOrder[] $completed_orders
 * @property-read int|null $completed_orders_count
 * @property-read Collection|OrderConversation[] $conversation
 * @property-read int|null $conversation_count
 * @property-read Collection|OrderConversationTalk[] $conversation_sender
 * @property-read int|null $conversation_sender_count
 * @method static Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $patronymic
 * @method static Builder|Client whereName($value)
 * @method static Builder|Client wherePatronymic($value)
 * @method static Builder|Client whereSurname($value)
 * @property int $only_passenger
 * @property-read Collection|OrderFeedback[] $assessed
 * @property-read int|null $assessed_count
 * @property-read Collection|OrderFeedback[] $rater
 * @property-read int|null $rater_count
 * @method static Builder|Client whereOnlyPassenger($value)
 * @property-read FcmClient|null $fcm
 * @property-read Order|null $order
 * @property-read Collection|PreOrder[] $preorders
 * @property-read int|null $preorders_count
 * @property-read Collection|Driver[] $current_order_driver
 * @property-read int|null $current_order_driver_count
 * @property-read Collection|OrderInProcessRoad[] $current_order_in_process_road
 * @property-read int|null $current_order_in_process_road_count
 * @property-read Collection|OrderOnWayRoad[] $current_order_on_way_road
 * @property-read int|null $current_order_on_way_road_count
 * @property-read Collection|OrderProcess[] $current_order_process
 * @property-read int|null $current_order_process_count
 * @property-read OrderShippedDriver|null $current_shipped
 * @property-read Collection|PayCard[] $pay_cards
 * @property-read int|null $pay_cards_count
 * @property-read Collection|FranchiseTransaction[] $transactions
 * @property-read int|null $transactions_count
 * @property-read string $full_name
 * @method static Builder|ServiceAuthenticable cordDistance($latitude, $longitude)
 * @property-read Collection|PreOrder[] $pre_orders
 * @property-read int|null $pre_orders_count
 */
class Client extends ServiceAuthenticable
{
    use SoftDeletes;

    protected const LATITUDE = 'lat';
    protected const LONGITUDE = 'lut';
    protected static bool $kilometers = false;
    /**
     * @var string
     */
    public $username = 'phone';
    /**
     * @var string
     */
    public $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d'
    ];
    /**
     * @var string[]
     */
    public $socketAuth = ['client_id', 'phone'];
    /**
     * @var string
     */
    protected $guard_name = 'clients_api';
    /**
     * @var string
     */
    protected $guard = 'clients_api';
    /**
     * @var string
     */
    protected $table = 'clients';
    /**
     * @var string
     */
    protected $primaryKey = 'client_id';
    /**
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    /**
     * @var array
     */
    protected $fillable = [
        'phone',
        'name',
        'surname',
        'patronymic',
        'email',
        'logged',
        'online',
        'in_order',
        'mean_assessment',
        'password',
    ];

    /**
     * @var string
     */
    protected string $map = 'client';


    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->surname.' '.$this->name.' '.$this->patronymic;
    }


    /*=============================================================================================
                                            RELATIONS
    =============================================================================================*/

    /**
     * @return HasManyDeep
     */
    public function corporateCompanies(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->corporate(), (new CorporateClient())->company());
    }

    /**
     * @return HasMany
     */
    public function corporate(): HasMany
    {
        return $this->hasMany(CorporateClient::class, 'client_id', 'client_id');
    }

    /**
     * @return HasOneDeep
     */
    public function current_order_driver(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->hasOneThrough(OrderShippedDriver::class, Order::class, 'client_id', 'order_id'),
            (new OrderShippedDriver())->driver())
            ->where('order_shipped_drivers.current', '=', true)
            ->where('order_shipped_drivers.status_id', '=', OrderShippedStatus::PRE_ACCEPTED);
    }

    /**
     * @return HasOneDeep
     */
    public function current_order_process(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->hasOneThrough(OrderShippedDriver::class, Order::class, 'client_id', 'order_id'),
            (new OrderShippedDriver())->process())
            ->where('order_shipped_drivers.current', '=', true)
            ->where('order_shipped_drivers.status_id', '=', OrderShippedStatus::PRE_ACCEPTED);
    }

    /**
     * @return HasOneDeep
     */
    public function current_order_on_way_road(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->hasOneThrough(OrderShippedDriver::class, Order::class, 'client_id', 'order_id'),
            (new OrderShippedDriver())->on_way_road())
            ->where('order_shipped_drivers.current', '=', true)
            ->where('order_shipped_drivers.status_id', '=', OrderShippedStatus::PRE_ACCEPTED);
    }

    /**
     * @return HasOneDeep
     */
    public function current_order_in_process_road(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->hasOneThrough(OrderShippedDriver::class, Order::class, 'client_id', 'order_id'),
            (new OrderShippedDriver())->in_process_road())
            ->where('order_shipped_drivers.current', '=', true)
            ->where('order_shipped_drivers.status_id', '=', OrderShippedStatus::PRE_ACCEPTED);
    }

    /**
     * @return HasOne
     * @TODO used OrderEndService (clientCancelOrder)
     */
    public function current_order(): HasOne
    {
        return $this->hasOne(Order::class, $this->primaryKey, $this->primaryKey)
            ->where('status_id', '!=', OrderStatus::ORDER_COMPLETED)
            ->where('status_id', '!=', OrderStatus::ORDER_CANCELED)
            ->whereDoesntHave('preorder')
            ->orWhereHas('preorder', fn(Builder $query) => $query
                ->where('time', '<=', now()->addMinutes(30))
                ->where('active', '=', true)
                ->whereHas('common', fn($query) => $query->where('accept', '=', true))
                ->whereHas('order', fn($query) => $query
                    ->where('status_id', '!=', OrderStatus::ORDER_COMPLETED)
                    ->where('status_id', '!=', OrderStatus::ORDER_CANCELED)
                )
            );
    }

    /**
     * @return HasOneThrough
     */
    public function current_shipped(): HasOneThrough
    {
        return $this->hasOneThrough(OrderShippedDriver::class, Order::class, 'client_id', 'order_id')
            ->where('order_shipped_drivers.current', '=', true)
            ->where('order_shipped_drivers.status_id', '=', OrderShippedStatus::PRE_ACCEPTED);
    }

    /**
     * @return HasManyThrough
     */
    public function preorders(): HasManyThrough
    {
        return $this->hasManyThrough(PreOrder::class, Order::class, 'client_id', 'order_id');
    }

    /**
     * @return HasOne
     */
    public function cancel_order(): HasOne
    {
        return $this->hasOne(Order::class, $this->primaryKey)
            ->where('status_id', '!=', OrderStatus::getStatusId(OrderStatus::ORDER_CANCELED))
            ->where('status_id', '!=', OrderStatus::getStatusId(OrderStatus::ORDER_COMPLETED))
            ->where('status_id', '!=', OrderStatus::getStatusId(OrderStatus::ORDER_PAUSED));
    }

    /**
     * @return HasOneThrough
     */
    public function completed_order(): HasOneThrough
    {
        return $this->hasOneThrough(CompletedOrder::class, Order::class, 'client_id', 'order_id');
    }

    /**
     * @return HasManyThrough
     */
    public function completed_orders(): HasManyThrough
    {
        return $this->hasManyThrough(CompletedOrder::class, Order::class, 'client_id', 'order_id');
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, $this->primaryKey);
    }

    /**
     * @return HasOne
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class, $this->primaryKey);
    }

    /**
     * @return HasMany
     */
    public function corporateOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'passenger_id', 'client_id');
    }

    /**
     * @return HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(ClientAddress::class, 'client_id', 'client_id');
    }

    /**
     * @return BelongsToMany
     */
    public function favoriteDrivers(): BelongsToMany
    {
        return $this->belongsToMany(Driver::class, 'client_favorite_driver', 'client_id', 'driver_id');
    }

    /**
     * @return HasManyThrough
     */
    public function pre_orders(): HasManyThrough
    {
        return $this->hasManyThrough(PreOrder::class, Order::class, 'client_id', 'order_id');
    }

    /**
     * @return MorphOne
     */
    public function drivers_view(): MorphOne
    {
        return $this->morphOne(ClientTaxiView::class, 'client', 'clientable_type', 'clientable_id', $this->primaryKey);
    }

    /**
     * @return MorphMany
     */
    public function drivers_views(): MorphMany
    {
        return $this->morphMany(ClientTaxiView::class, 'client', 'clientable_type', 'clientable_id', $this->primaryKey)->withTrashed();
    }

    /**
     * @return MorphMany
     */
    public function completed_feedbacks(): MorphMany
    {
        return $this->morphMany(OrderFeedback::class, 'writable')->whereHasMorph('orderable', (new CompletedOrder())->getMap());
    }

    /**
     * @return MorphMany
     */
    public function canceled_feedbacks(): MorphMany
    {
        return $this->morphMany(OrderFeedback::class, 'writable')->whereHasMorph('orderable', (new CanceledOrder())->getMap());
    }

    /**
     * @return MorphOne
     */
    public function current_canceled_order(): MorphOne
    {
        return $this->morphOne(CanceledOrder::class, 'cancelable');
    }

    /**
     * @return MorphMany
     */
    public function canceled_orders(): MorphMany
    {
        return $this->morphMany(CanceledOrder::class, 'cancelable');
    }

    /**
     * @return MorphMany
     */
    public function call(): MorphMany
    {
        return $this->morphMany(ClientCall::class, 'callable');
    }

    /**
     * @return MorphOne
     */
    public function session_info(): MorphOne
    {
        return $this->morphOne(ClientSessionInfo::class, 'client', 'clientable_type', 'clientable_id', $this->primaryKey);
    }

    /**
     * @return MorphMany
     */
    public function all_session_info(): MorphMany
    {
        return $this->morphMany(ClientSessionInfo::class, 'client', 'clientable_type', 'clientable_id', $this->primaryKey)->withTrashed();
    }

    /**
     * @return HasOne
     */
    public function before_auth(): HasOne
    {
        return $this->hasOne(BeforeAuthClient::class, $this->primaryKey);
    }

    /**
     * @return HasMany
     */
    public function before_auths(): HasMany
    {
        return $this->hasMany(BeforeAuthClient::class)->withTrashed();
    }

    /**
     * @return HasOne
     */
    public function setting(): HasOne
    {
        return $this->hasOne(ClientSetting::class, $this->primaryKey);
    }

    /**
     * @return HasOneDeep
     */
    public function initial_order(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->initial_order_data(), (new OrderInitialData())->order())->with('initial_data');
    }

    /**
     * @return MorphOne
     */
    public function initial_order_data(): MorphOne
    {
        return $this->morphOne(OrderInitialData::class, 'orderable', 'orderable_type', 'orderable_id', $this->primaryKey);
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
     * @return \Illuminate\Support\Collection
     */
    public function coordinate(): \Illuminate\Support\Collection
    {
        if ($this->in_order && $this->current_order->show_cord) {
            return collect(['lat' => $this->current_order->lat, 'lut' => $this->current_order->lut]);
        }

        return collect(['lat' => $this->initial_order->lat, 'lut' => $this->initial_order->lut]);
    }

    /**
     * @return MorphMany
     */
    public function conversation(): MorphMany
    {
        return $this->morphMany(OrderConversation::class, 'client', 'client_type', 'client_id');
    }

    /**
     * @return MorphMany
     */
    public function conversation_sender(): MorphMany
    {
        return $this->morphMany(OrderConversationTalk::class, 'sender', 'sender_type', 'sender_id');
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
    public function pay_cards(): MorphMany
    {
        return $this->morphMany(PayCard::class, 'owner');
    }

    /**
     * @return MorphMany
     */
    public function transactions(): MorphMany
    {
        return $this->morphMany(FranchiseTransaction::class, 'side', 'side_type', 'side_id');
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
     * @return Builder|OrderFeedbackOption
     */
    public function scopeCanceledOrderFeedback()
    {
        return (new OrderFeedbackOption())
            ::where('owner_type', '=', (new self())->getMap())
            ->where('canceled', '=', true)
            ->where('completed', '=', false);
    }

    /**
     * @return Builder|OrderFeedbackOption
     */
    public function scopeResetedOrderFeedback(): Builder|OrderFeedbackOption
    {
        return (new OrderFeedbackOption())
            ::where('owner_type', '=', self::class)
            ->where('canceled', '=', false)
            ->where('completed', '=', false)
            ->where('reseted', '=', true);
    }

    ////////////////////////////////////////////////////////////END SCOPES////////////////////////////////////////////////

    /**
     * @param $phone
     */
    public function setPhoneAttribute($phone): void
    {
        $this->attributes['phone'] = preg_replace('/[\D]/', '', $phone);
    }

    /**
     * @param $phone
     * @return object|null
     */
    public function findForPassport($phone): object|null
    {
        return self::where($this->username, '=', $phone)->first();
    }

    /**
     * @param $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password): bool
    {
        return Hash::check($password, $this->password);
    }
}
