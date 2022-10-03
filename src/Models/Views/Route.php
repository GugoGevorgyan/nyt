<?php

declare(strict_types=1);

namespace Src\Models\Views;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Role\Role;

/**
 * Class Route
 *
 * @property mixed $url
 * @package Src\Models\Views
 * @property int $route_id
 * @property string|null $namespace
 * @property string|null $name
 * @property string|null $type
 * @property string|null $alias
 * @property string|null $as
 * @property mixed|null $middleware
 * @property string|null $prefix
 * @property-read Collection|Role[] $role
 * @property-read int|null $role_count
 * @method static Builder|Route newModelQuery()
 * @method static Builder|Route newQuery()
 * @method static Builder|Route query()
 * @method static Builder|Route whereAlias($value)
 * @method static Builder|Route whereAs($value)
 * @method static Builder|Route whereMiddleware($value)
 * @method static Builder|Route whereName($value)
 * @method static Builder|Route whereNamespace($value)
 * @method static Builder|Route wherePrefix($value)
 * @method static Builder|Route whereRouteId($value)
 * @method static Builder|Route whereType($value)
 * @method static Builder|Route whereUrl($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
class Route extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'routes';

    /**
     * @var string
     */
    protected $primaryKey = 'route_id';

    /**
     * @var array
     */
    protected $fillable = ['namespace', 'name', 'type', 'url', 'alias', 'middleware'];

    /**
     * @return BelongsToMany
     */
    public function role(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'route_role', 'route_id', 'role_id');
    }
}
