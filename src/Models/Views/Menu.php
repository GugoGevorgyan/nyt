<?php

declare(strict_types=1);

namespace Src\Models\Views;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Role\Role;

/**
 * Class Menu
 *
 * @package Src\Models
 * @property int $menu_id
 * @property int|null $route_id
 * @property int|null $parent_id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $icon
 * @method static Builder|Menu newModelQuery()
 * @method static Builder|Menu newQuery()
 * @method static Builder|Menu query()
 * @method static Builder|Menu whereDescription($value)
 * @method static Builder|Menu whereIcon($value)
 * @method static Builder|Menu whereMenuId($value)
 * @method static Builder|Menu whereParentId($value)
 * @method static Builder|Menu whereRouteId($value)
 * @method static Builder|Menu whereTitle($value)
 * @mixin Eloquent
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Route|null $route
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Menu whereCreatedAt($value)
 * @method static Builder|Menu whereUpdatedAt($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel within($geometryColumn, $polygon)
 * @property-read Collection|Menu[] $child
 * @property-read int|null $child_count
 * @property-read Menu|null $parent
 */
class Menu extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'menus';
    /**
     * @var string
     */
    protected $primaryKey = 'menu_id';
    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'icon'];

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this
            ->belongsToMany(Role::class, 'menu_role', 'menu_id', 'role_id')
            ->with('permissions');
    }

    /**
     * @return BelongsTo
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id','menu_id');
    }

    /**
     * @return HasMany
     */
    public function child(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id','menu_id');
    }
}
