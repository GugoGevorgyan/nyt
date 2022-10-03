<?php

declare(strict_types=1);

namespace Src\Models\SystemUsers;

use Src\Models\Franchise\Franchise;
use Src\Models\Oauth\Client;
use Src\Models\Oauth\FcmClient;
use Src\Models\Oauth\Token;
use Src\Models\Role\Role;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceAuthenticable;

/**
 * Class SuperFranchiser
 *
 * @package Src\Models
 * @property-read ElasticquentCollection|SystemWorker[] $franchisers
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @method static Builder|SuperAdmin newModelQuery()
 * @method static Builder|SuperAdmin newQuery()
 * @method static Builder|SuperAdmin query()
 * @mixin Eloquent
 * @property int $super_admin_id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $remember_token
 * @property string|null $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read Collection|Franchise[] $franchisee
 * @property-read int|null $franchisee_count
 * @property-read int|null $notifications_count
 * @property-read Image $profile_image
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|SuperAdmin whereCreatedAt($value)
 * @method static Builder|SuperAdmin whereEmail($value)
 * @method static Builder|SuperAdmin whereName($value)
 * @method static Builder|SuperAdmin wherePassword($value)
 * @method static Builder|SuperAdmin whereRememberToken($value)
 * @method static Builder|SuperAdmin whereSuperAdminId($value)
 * @method static Builder|SuperAdmin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property-read Collection|\Src\Models\Role\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read FcmClient|null $fcm
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable cordDistance($latitude, $longitude)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable within($geometryColumn, $polygon)
 */
class SuperAdmin extends ServiceAuthenticable
{
    public const SUPER_ADMIN_SHOW_FRANCHISEE_WEB = 'show_franchisee_web';
    public const SUPER_ADMIN_SHOW_FRANCHISEE_API = 'show_franchisee_api';

    public const SUPER_ADMIN_CREATE_FRANCHISEE_WEB = 'create_franchisee_web';
    public const SUPER_ADMIN_CREATE_FRANCHISEE_API = 'create_franchisee_api';

    public const SUPER_ADMIN_EDIT_FRANCHISEE_WEB = 'edit_franchisee_web';
    public const SUPER_ADMIN_EDIT_FRANCHISEE_API = 'edit_franchisee_api';

    public const SUPER_ADMIN_REMOVE_FRANCHISEE_WEB = 'remove_franchisee_web';
    public const SUPER_ADMIN_REMOVE_FRANCHISEE_API = 'remove_franchisee_api';

    public const SUPER_ADMIN_DELETE_FRANCHISEE_WEB = 'delete_franchisee_web';
    public const SUPER_ADMIN_DELETE_FRANCHISEE_API = 'delete_franchisee_api';

    public const SUPER_ADMIN_SHOW_FRANCHISE_ADMIN_WEB = 'show_franchise_admin_web';
    public const SUPER_ADMIN_SHOW_FRANCHISE_ADMIN_API = 'show_franchise_admin_api';

    public const SUPER_ADMIN_CREATE_FRANCHISE_ADMIN_WEB = 'create_franchise_admin_web';
    public const SUPER_ADMIN_CREATE_FRANCHISE_ADMIN_API = 'create_franchise_admin_api';

    public const SUPER_ADMIN_EDIT_FRANCHISE_ADMIN_WEB = 'edit_franchise_admin_web';
    public const SUPER_ADMIN_EDIT_FRANCHISE_ADMIN_API = 'edit_franchise_admin_api';

    public const SUPER_ADMIN_REMOVE_FRANCHISE_ADMIN_WEB = 'remove_franchise_admin_web';
    public const SUPER_ADMIN_REMOVE_FRANCHISE_ADMIN_API = 'remove_franchise_admin_api';

    public const SUPER_ADMIN_DELETE_FRANCHISE_ADMIN_WEB = 'delete_franchise_admin_web';
    public const SUPER_ADMIN_DELETE_FRANCHISE_ADMIN_API = 'delete_franchise_admin_api';


    /**
     * @var string
     */
    protected $guard_name = 'admin_super_web';

    /**
     * @var string
     */
    protected $table = 'super_admin';

    /**
     * @var string
     */
    protected $primaryKey = 'super_admin_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return HasMany
     */
    public function franchisee(): HasMany
    {
        return $this->hasMany(Franchise::class, 'creator_admin_id', $this->primaryKey);
    }

    /**
     * @return MorphOne
     */
    public function profile_image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * @return MorphOne
     */
    public function fcm(): MorphOne
    {
        return $this->morphOne(FcmClient::class, 'client', 'client_type', 'client_id');
    }
}
