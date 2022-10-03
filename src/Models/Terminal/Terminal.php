<?php

declare(strict_types=1);

namespace Src\Models\Terminal;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceAuthenticable;
use Src\Models\Driver\Driver;
use Src\Models\Oauth\Client;
use Src\Models\Oauth\Token;
use Src\Models\Park;
use Src\Models\Role\Permission;
use Src\Models\Role\Role;

/**
 * Class Terminal
 *
 * @package Src\Models\Terminal
 * @property int $terminal_id
 * @property int|null $park_id
 * @property string|null $name
 * @property string|null $hash
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Park|null $park
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|Terminal newModelQuery()
 * @method static Builder|Terminal newQuery()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|Terminal query()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|Terminal whereCreatedAt($value)
 * @method static Builder|Terminal whereHash($value)
 * @method static Builder|Terminal whereName($value)
 * @method static Builder|Terminal whereParkId($value)
 * @method static Builder|Terminal whereTerminalId($value)
 * @method static Builder|Terminal whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|Waybill[] $waybills
 * @property-read int|null $waybills_count
 * @method static Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $auth_driver_id
 * @property string $password
 * @property-read Driver $auth_driver
 * @method static Builder|Terminal whereAuthDriverId($value)
 * @method static Builder|Terminal wherePassword($value)
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
class Terminal extends ServiceAuthenticable
{
    /**
     * @var string
     */
    protected $table = 'terminals';
    /**
     * @var string
     */
    protected $primaryKey = 'terminal_id';
    /**
     * @var string[]
     */
    protected $fillable = ['park_id', 'auth_driver_id', 'name', 'password'];
    /**
     * @var string
     */
    protected $username = 'name';
    /**
     * @var string
     */
    protected string $map = 'terminal';

    /**
     * @return BelongsTo
     */
    public function park(): BelongsTo
    {
        return $this->belongsTo(Park::class, 'park_id');
    }

    /**
     * @return HasMany
     */
    public function waybills(): HasMany
    {
        return $this->hasMany(Waybill::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @param $username
     * @return Builder|Model|object|Terminal|null
     */
    public function findForPassport($username)
    {
        return static::where($this->username, '=', $username)->first();
    }

    /**
     * @return BelongsTo
     */
    public function auth_driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'auth_driver_id');
    }
}
