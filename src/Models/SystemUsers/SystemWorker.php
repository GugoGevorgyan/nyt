<?php

declare(strict_types=1);

namespace Src\Models\SystemUsers;

use Eloquent;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceAuthenticable;
use Src\Models\Chat\Message;
use Src\Models\Chat\Room;
use Src\Models\Driver\DriverCandidate;
use Src\Models\Franchise\Franchise;
use Src\Models\Franchise\FranchiseTransaction;
use Src\Models\Oauth\Client;
use Src\Models\Oauth\FcmClient;
use Src\Models\Oauth\Token;
use Src\Models\Order\CanceledOrder;
use Src\Models\Order\Order;
use Src\Models\Park;
use Src\Models\Role\Permission;
use Src\Models\Role\Role;
use Src\Models\Role\WorkerPermission;
use Src\Models\Role\WorkerRole;
use Src\Models\SystemWorker\WorkerSession;
use Src\Models\Terminal\Waybill;
use Src\Models\Views\Image;

/**
 * Class SystemWorker
 *
 * @package Src\Models
 * @property int $system_worker_id
 * @property int|null $franchise_id
 * @property int|null $is_admin
 * @property int|null $schedule_id график работы
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string|null $nickname
 * @property string|null $email
 * @property string|null $remember_token
 * @property string|null $password
 * @property string|null $phone
 * @property string|null $description
 * @property string|null $photo
 * @property float|null $salary
 * @property float|null $prize
 * @property int|null $rating
 * @property int $logged
 * @property int $in_session
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|CanceledOrder[] $canceled_orders
 * @property-read int|null $canceled_orders_count
 * @property-read Collection|DriverCandidate[] $candidate_tutor
 * @property-read int|null $candidate_tutor_count
 * @property-read int|null $chat_participants_count
 * @property-read Collection|Room[] $chat_rooms
 * @property-read int|null $chat_rooms_count
 * @property-read Collection|Message[] $chat_sender
 * @property-read int|null $chat_sender_count
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read CanceledOrder|null $current_canceled_order
 * @property-read Franchise|null $franchise
 * @property-read Franchise $franchise_worker
 * @property-read Franchise|null $franchisee_owner
 * @property-read Collection|WorkerSession[] $logged_sessions
 * @property-read int|null $logged_sessions_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read Collection|Park[] $parks_owner_admin
 * @property-read int|null $parks_owner_admin_count
 * @property-read Image|null $profile_image
 * @property-read Collection|WorkerSession[] $quit_sessions
 * @property-read int|null $quit_sessions_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read WorkerGraphic|null $schedule
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read Collection|Waybill[] $waybills
 * @property-read int|null $waybills_count
 * @property-read WorkerDispatcher|null $worker_dispatcher
 * @property-read WorkerOperator|null $worker_operator
 * @property-read Collection|Permission[] $worker_permissions
 * @property-read int|null $worker_permissions_count
 * @property-read Collection|WorkerRole[] $worker_role_ids
 * @property-read int|null $worker_role_ids_count
 * @property-read Collection|Role[] $worker_roles
 * @property-read int|null $worker_roles_count
 * @method static Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|SystemWorker newModelQuery()
 * @method static Builder|SystemWorker newQuery()
 * @method static \Illuminate\Database\Query\Builder|SystemWorker onlyTrashed()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|SystemWorker query()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|SystemWorker whereCreatedAt($value)
 * @method static Builder|SystemWorker whereDeletedAt($value)
 * @method static Builder|SystemWorker whereDescription($value)
 * @method static Builder|SystemWorker whereEmail($value)
 * @method static Builder|SystemWorker whereFranchiseId($value)
 * @method static Builder|SystemWorker whereInSession($value)
 * @method static Builder|SystemWorker whereIsAdmin($value)
 * @method static Builder|SystemWorker whereLogged($value)
 * @method static Builder|SystemWorker whereName($value)
 * @method static Builder|SystemWorker whereNickname($value)
 * @method static Builder|SystemWorker wherePassword($value)
 * @method static Builder|SystemWorker wherePatronymic($value)
 * @method static Builder|SystemWorker wherePhone($value)
 * @method static Builder|SystemWorker wherePhoto($value)
 * @method static Builder|SystemWorker wherePrize($value)
 * @method static Builder|SystemWorker whereRating($value)
 * @method static Builder|SystemWorker whereRememberToken($value)
 * @method static Builder|SystemWorker whereSalary($value)
 * @method static Builder|SystemWorker whereScheduleId($value)
 * @method static Builder|SystemWorker whereSurname($value)
 * @method static Builder|SystemWorker whereSystemWorkerId($value)
 * @method static Builder|SystemWorker whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SystemWorker withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SystemWorker withoutTrashed()
 * @mixin Eloquent
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property int|null $graphic_id график работы
 * @method static Builder|SystemWorker whereGraphicId($value)
 * @property-read FcmClient|null $fcm
 * @property-read Collection|FranchiseTransaction[] $transactions
 * @property-read int|null $transactions_count
 * @property-read string $full_name
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable cordDistance($latitude, $longitude)
 */
class SystemWorker extends ServiceAuthenticable
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected string $guard_name = 'system_workers_web';
    /**
     * @var string
     */
    protected string $guard = 'system_workers_web';
    /**
     * @var string
     */
    protected $table = 'system_workers';
    /**
     * @var string
     */
    protected $primaryKey = 'system_worker_id';
    /**
     * @var string
     */
    protected string $username = 'nickname';
    /**
     * @var string[]
     */
    protected $fillable = [
        'is_admin',
        'franchise_id',
        'graphic_id',
        'name',
        'surname',
        'patronymic',
        'nickname',
        'email',
        'phone',
        'photo',
        'rating',
        'description',
        'password',
        'logged',
        'in_session'
    ];
    /**
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * @var string
     */
    protected string $map = 'systemWorker';

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->surname.' '.$this->name.' '.$this->patronymic;
    }

    /**
     * @param $phone
     */
    public function setPhoneAttribute($phone): void
    {
        $this->attributes['phone'] = preg_replace('/[\D]/', '', $phone);
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
    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class, 'franchise_id', 'franchise_id');
    }

    /**
     * @return BelongsToMany
     */
    public function parks(): BelongsToMany
    {
        return $this
            ->belongsToMany(Park::class, 'park_mechanic', 'mechanic_id', 'park_id')
            ->where('franchise_id', FRANCHISE_ID);
    }

    /**
     * @return BelongsTo
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(WorkerGraphic::class, 'schedule_id', 'worker_graphic_id');
    }

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'worker_permission', 'system_worker_id', 'permission_id');
    }

    /**
     * @return HasMany
     */
    public function worker_permissions(): HasMany
    {
        return $this->hasMany(WorkerPermission::class, 'system_worker_id', 'system_worker_id');
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'worker_role', 'system_worker_id', 'role_id');
    }

    /**
     * @return HasMany
     */
    public function worker_roles(): HasMany
    {
        return $this->hasMany(WorkerRole::class, 'system_worker_id', 'system_worker_id');
    }

    /**
     * @param $username
     * @return mixed
     */
    public function findForPassport($username)
    {
        return static::where($this->username, '=', $username)->first();
    }

    /**
     * @return HasMany
     */
    public function waybills(): HasMany
    {
        return $this->hasMany(Waybill::class, 'system_worker_id', 'system_worker_id');
    }

    /**
     * @return MorphMany
     */
    public function chat_rooms(): MorphMany
    {
        return $this->morphMany(Room::class, 'roomable');
    }

    /**
     * @return MorphMany
     */
    public function chat_sender(): MorphMany
    {
        return $this->morphMany(Message::class, 'senderable');
    }

    /**
     * @return MorphMany
     */
    public function canceled_orders(): MorphMany
    {
        return $this->morphMany(CanceledOrder::class, 'cancelable');
    }

    /**
     * @return MorphOne
     */
    public function current_canceled_order(): MorphOne
    {
        return $this->morphOne(CanceledOrder::class, 'cancelable');
    }

    /**
     * @return HasOne
     */
    public function worker_operator(): HasOne
    {
        return $this->hasOne(WorkerOperator::class, 'system_worker_id', 'system_worker_id');
    }

    /**
     * @return HasOne
     */
    public function worker_dispatcher(): HasOne
    {
        return $this->hasOne(WorkerDispatcher::class, 'system_worker_id', 'system_worker_id');
    }

    /**
     * @return HasMany
     */
    public function logged_sessions(): HasMany
    {
        return $this->hasMany(WorkerSession::class, 'logged_worker_id');
    }

    /**
     * @return HasMany
     */
    public function quit_sessions(): HasMany
    {
        return $this->hasMany(WorkerSession::class, 'quit_worker_id');
    }

    /**
     * @return MorphMany
     */
    public function orders(): MorphMany
    {
        return $this->morphMany(Order::class, 'customer', 'customer_type', 'customer_id');
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
    public function transactions(): MorphMany
    {
        return $this->morphMany(FranchiseTransaction::class, 'side', 'side_type', 'side_id');
    }
}
