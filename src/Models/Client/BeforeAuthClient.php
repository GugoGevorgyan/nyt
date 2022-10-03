<?php

declare(strict_types=1);

namespace Src\Models\Client;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;
use Src\Models\Oauth\Token;
use Src\Models\Order\OrderInitialData;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use ServiceEntity\Models\ServiceAuthenticable;
use Src\Models\Role\Role;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

/**
 * Class BeforeAuthClient
 *
 * @package Src\Models\Client
 * @property int $before_auth_client_id
 * @property int|null $client_id
 * @property string $hash_name
 * @property string $hash
 * @property string|null $remember_token
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|ClientSessionInfo[] $all_session_info
 * @property-read int|null $all_session_info_count
 * @property-read Client|null $client
 * @property-read Collection|\Src\Models\Oauth\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read ClientCoordinate|null $coordinate
 * @property-read Collection|ClientCoordinate[] $coordinates
 * @property-read int|null $coordinates_count
 * @property-read ClientTaxiView|null $drivers_view
 * @property-read Collection|ClientTaxiView[] $drivers_views
 * @property-read int|null $drivers_views_count
 * @property-read OrderInitialData|null $initial_order_data
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read ClientSessionInfo|null $session_info
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|BeforeAuthClient newModelQuery()
 * @method static Builder|BeforeAuthClient newQuery()
 * @method static \Illuminate\Database\Query\Builder|BeforeAuthClient onlyTrashed()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|BeforeAuthClient query()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|BeforeAuthClient whereBeforeAuthClientId($value)
 * @method static Builder|BeforeAuthClient whereClientId($value)
 * @method static Builder|BeforeAuthClient whereCreatedAt($value)
 * @method static Builder|BeforeAuthClient whereDeletedAt($value)
 * @method static Builder|BeforeAuthClient whereHash($value)
 * @method static Builder|BeforeAuthClient whereHashName($value)
 * @method static Builder|BeforeAuthClient whereRememberToken($value)
 * @method static Builder|BeforeAuthClient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|BeforeAuthClient withTrashed()
 * @method static \Illuminate\Database\Query\Builder|BeforeAuthClient withoutTrashed()
 * @mixin Eloquent
 * @method static Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|\Src\Models\Role\Permission[] $permissions
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
class BeforeAuthClient extends ServiceAuthenticable
{
    /**
     * @var string
     */
    protected $guard_name = 'before_clients_web';

    /**
     * @var string
     */
    protected $guard = 'before_clients_web';

    /**
     * @var string
     */
    protected $primaryKey = 'before_auth_client_id';

    /**
     * @var string
     */
    protected $table = 'before_auth_clients';

    /**
     * @var array
     */
    protected $fillable = ['client_id', 'hash', 'hash_name'];

    /**
     * @var string
     */
    protected string $map = 'beforeClient';

    /*=============================================================================================
                                            RELATIONS
    =============================================================================================*/
    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
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
     * @return \Illuminate\Support\Collection
     */
    public function coordinate(): \Illuminate\Support\Collection
    {
        return collect(['lat' => $this->initial_order_data->lat, 'lut' => $this->initial_order_data->lut]);
    }

    /**
     * @return MorphOne
     */
    public function drivers_view(): MorphOne
    {
        return $this->morphOne(ClientTaxiView::class, 'before_client', 'clientable_type', 'clientable_id', $this->primaryKey);
    }

    /**
     * @return MorphMany
     */
    public function drivers_views(): MorphMany
    {
        return $this->morphMany(ClientTaxiView::class, 'before_client', 'clientable_type', 'clientable_id', $this->primaryKey)->withTrashed();
    }

    /**
     * @return MorphOne
     */
    public function initial_order_data(): MorphOne
    {
        return $this->morphOne(OrderInitialData::class, 'orderable');
    }

    /**
     * @return HasOneDeep
     */
    public function initial_order(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->initial_order_data(), (new OrderInitialData())->order());
    }
}
