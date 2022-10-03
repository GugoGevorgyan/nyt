<?php

declare(strict_types=1);

namespace Src\Models\Corporate;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceAuthenticable;
use Src\Models\Franchise\Franchise;
use Src\Models\Oauth\Client;
use Src\Models\Oauth\Token;
use Src\Models\Role\Permission;
use Src\Models\Role\Role;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

/**
 * Class AdminCorporate
 *
 * @package Src\Models
 * @property int $admin_corporate_id
 * @property int|null $park_id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $remember_token
 * @property string|null $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $clients
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read Collection|Permission[] $permissions
 * @property-read Collection|Role[] $roles
 * @property-read Collection|Token[] $tokens
 * @method static Builder|AdminCorporate newModelQuery()
 * @method static Builder|AdminCorporate newQuery()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|AdminCorporate query()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|AdminCorporate whereAdminCorporateId($value)
 * @method static Builder|AdminCorporate whereCreatedAt($value)
 * @method static Builder|AdminCorporate whereEmail($value)
 * @method static Builder|AdminCorporate whereName($value)
 * @method static Builder|AdminCorporate whereParkId($value)
 * @method static Builder|AdminCorporate wherePassword($value)
 * @method static Builder|AdminCorporate whereRememberToken($value)
 * @method static Builder|AdminCorporate whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Company $company
 * @property int|null $franchise_id
 * @property-read int|null $clients_count
 * @property-read Franchise $franchise
 * @property-read int|null $notifications_count
 * @property-read int|null $roles_count
 * @property-read int|null $tokens_count
 * @method static Builder|AdminCorporate whereFranchiseId($value)
 * @property int $company_id
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string|null $phone
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|AdminCorporate whereCompanyId($value)
 * @method static Builder|AdminCorporate wherePatronymic($value)
 * @method static Builder|AdminCorporate wherePhone($value)
 * @method static Builder|AdminCorporate whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
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
 * @property-read string $full_name
 */
class AdminCorporate extends ServiceAuthenticable
{
    /**
     * @var string
     */
    protected $table = 'admin_corporates';

    /**
     * @var string
     */
    protected $primaryKey = 'admin_corporate_id';

    /**
     * @var array
     */
    protected $fillable = [
        'company_id',
        'franchise_id',
        'name',
        'surname',
        'patronymic',
        'email',
        'phone',
        'password'
    ];

    /**
     * @var array
     */
    protected $guarded = ['password'];

    /**
     * @var string
     */
    protected $guard_name = 'admin_corporate_web';

    /**
     * @var string
     */
    protected string $map = 'adminCorporate';

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    /**
     * @return HasOne
     */
    public function franchise(): HasOne
    {
        return $this->hasOne(Franchise::class, 'franchise_id', 'franchise_id');
    }

    /**
     * @return HasManyDeep
     */
    public function franchise_cities(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->franchise(), (new Franchise())->cities());
    }
}
