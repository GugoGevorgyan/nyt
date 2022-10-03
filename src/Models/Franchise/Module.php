<?php

declare(strict_types=1);

namespace Src\Models\Franchise;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Core\Traits\HasFranchise;
use Src\Models\Role\Permission;
use Src\Models\Role\Role;
use Src\Models\Views\Route;

/**
 * Class Module
 *
 * @package Src\Models\Franchise
 * @property int $module_id
 * @property string|null $name
 * @property string|null $slug_name
 * @property string|null $description
 * @property int $default
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Franchise[] $franchisee
 * @property-read Collection|Permission[] $permissions
 * @property-read Collection|Role[] $roles
 * @method static Builder|Module newModelQuery()
 * @method static Builder|Module newQuery()
 * @method static Builder|Module query()
 * @method static Builder|Module whereCreatedAt($value)
 * @method static Builder|Module whereDefault($value)
 * @method static Builder|Module whereDescription($value)
 * @method static Builder|Module whereModuleId($value)
 * @method static Builder|Module whereName($value)
 * @method static Builder|Module whereSlugName($value)
 * @method static Builder|Module whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int|null $route_id
 * @property string|null $icon
 * @property-read Route $route
 * @method static Builder|Module whereIcon($value)
 * @method static Builder|Module whereRouteId($value)
 * @property string $alias
 * @property Carbon|null $deleted_at
 * @property-read int|null $franchisee_count
 * @property-read int|null $permissions_count
 * @property-read int|null $roles_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Module onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Module whereAlias($value)
 * @method static Builder|Module whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Module withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Module withoutTrashed()
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property string $text
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|Module whereText($value)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class Module extends ServiceModel
{
    use HasFranchise;

    public const CALL_CENTER = 'call_center';

    public const TERMINAL_WAYBILLS = 'terminal_waybills';

    public const ACCOUNTING = 'accounting';

    public const DRIVERS_CORPORATE = 'drivers_corporate';

    public const DRIVERS_AGGREGATOR = 'drivers_aggregator';

    public const DRIVERS_TENANT = 'drivers_tenant';

    public const MECHANIC = 'mechanic';

    public const PERSONAL_DEPARTMENT = 'personal_department';

    public const PARK_MECHANIC_APP = 'park_mechanic_app';

    public const CLIENT_APP = 'client_app';

    public const SECURITY_DEPARTMENT = 'security_department';

    /**
     * @var string
     */
    protected $table = 'modules';
    /**
     * @var string
     */
    protected $primaryKey = 'module_id';
    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'default', 'alias'];

    /**
     * @return BelongsToMany
     */
    public function franchisee(): BelongsToMany
    {
        return $this->belongsToMany(Franchise::class, 'franchise_module', 'module_id', 'franchise_id')
            ->withPivot('role_ids');
    }

    /**
     * @TODO REMOVE
     * @return BelongsTo
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, $this->primaryKey, 'route_id');
    }

    /**
     * @return HasMany
     */
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class, 'module_id', 'module_id');
    }

    /**
     * @return HasManyThrough
     */
    public function permissions(): HasManyThrough
    {
        return $this->hasManyThrough(Permission::class, Role::class, 'module_id', 'role_id', 'module_id', 'role_id');
    }
}
