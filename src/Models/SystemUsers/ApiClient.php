<?php

declare(strict_types=1);

namespace Src\Models\SystemUsers;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;
use Src\Models\Oauth\Client;
use Src\Models\Oauth\Token;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use ServiceEntity\Models\ServiceAuthenticable;
use Src\Models\Role\Permission;
use Src\Models\Role\Role;

/**
 * Class ApiClient
 *
 * @package Src\Models\Api
 * @property int $api_client_id
 * @property string $name
 * @property string $secret
 * @property int $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Token $oauth_access_token
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|\Src\Models\Api\ApiClient newModelQuery()
 * @method static Builder|\Src\Models\Api\ApiClient newQuery()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|\Src\Models\Api\ApiClient query()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|\Src\Models\Api\ApiClient whereApiClientId($value)
 * @method static Builder|\Src\Models\Api\ApiClient whereCreatedAt($value)
 * @method static Builder|\Src\Models\Api\ApiClient whereName($value)
 * @method static Builder|\Src\Models\Api\ApiClient whereSecret($value)
 * @method static Builder|\Src\Models\Api\ApiClient whereType($value)
 * @method static Builder|\Src\Models\Api\ApiClient whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
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
class ApiClient extends ServiceAuthenticable
{
    /**
     * CONST FOR CLIENT TYPES
     */
    public const CLIENT_TYPE_ATC = 1;
    public const CLIENT_TYPE_TERMINAL = 2;

    /**
     * @var string
     */
    protected $table = 'api_clients';
    /**
     * @var string
     */
    protected $primaryKey = 'api_client_id';
    /**
     * @var string
     */
    protected $username = 'name';
    /**
     * @var array
     */
    protected $fillable = ['name', 'secret', 'type'];

    /**
     * @return HasOne
     */
    public function oauth_access_token(): HasOne
    {
        return $this->hasOne(Token::class, 'user_id', 'api_client_id');
    }

    /**
     * @param $username
     *
     * @return Builder|Model|object|null
     */
    public function findForPassport($username)
    {
        return self::where($this->username, $username)->first();
    }

}
