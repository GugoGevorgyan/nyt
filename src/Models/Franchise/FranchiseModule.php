<?php

declare(strict_types=1);

namespace Src\Models\Franchise;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Role\FranchiseRole;
use Src\Models\Role\Role;

/**
 * Class FranchiseModule
 *
 * @package Src\Models\Franchise
 * @property int $franchise_module_id
 * @property int|null $franchise_id
 * @property int|null $module_id
 * @property array|null $role_ids
 * @property-read Collection|Role[] $roles
 * @method static Builder|FranchiseModule newModelQuery()
 * @method static Builder|FranchiseModule newQuery()
 * @method static Builder|FranchiseModule query()
 * @method static Builder|FranchiseModule whereFranchiseId($value)
 * @method static Builder|FranchiseModule whereFranchiseeModuleId($value)
 * @method static Builder|FranchiseModule whereModuleId($value)
 * @method static Builder|FranchiseModule whereRoleIds($value)
 * @mixin Eloquent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FranchiseModule whereCreatedAt($value)
 * @method static Builder|FranchiseModule whereUpdatedAt($value)
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|FranchiseRole[] $franchise_roles
 * @property-read int|null $franchise_roles_count
 * @property-read Module|null $module
 * @property-read int|null $roles_count
 * @method static Builder|FranchiseModule whereFranchiseModuleId($value)
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
class FranchiseModule extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'franchise_module';
    /**
     * @var string
     */
    protected $primaryKey = 'franchise_module_id';
    /**
     * @var array
     */
    protected $fillable = ['module_id', 'franchise_id'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];
    /**
     * @var array
     */
    protected $casts = [];

    /**
     * @return BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id', 'module_id');
    }

    /**
     * @return HasMany
     */
    public function franchise_roles(): HasMany
    {
        return $this->hasMany(FranchiseRole::class, 'franchise_module_id', 'franchise_module_id');
    }
}
