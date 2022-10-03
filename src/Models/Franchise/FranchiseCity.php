<?php

declare(strict_types=1);

namespace Src\Models\Franchise;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Franchise\FranchiseCity
 *
 * @property int $franchise_city_id
 * @property int $franchise_region_id
 * @property int $franchise_id
 * @property int $city_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|FranchiseCity newModelQuery()
 * @method static Builder|FranchiseCity newQuery()
 * @method static Builder|FranchiseCity query()
 * @method static Builder|FranchiseCity whereCityId($value)
 * @method static Builder|FranchiseCity whereCreatedAt($value)
 * @method static Builder|FranchiseCity whereFranchiseCityId($value)
 * @method static Builder|FranchiseCity whereFranchiseId($value)
 * @method static Builder|FranchiseCity whereFranchiseRegionId($value)
 * @method static Builder|FranchiseCity whereUpdatedAt($value)
 * @mixin Eloquent
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
class FranchiseCity extends ServiceModel
{
    /**
     * @var
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'franchise_city';
    /**
     * @var string
     */
    protected $primaryKey = 'franchise_city_id';
    /**
     * @var array
     */
    protected $fillable = ['city_id', 'franchise_region_id', 'franchise_id'];
    /**
     * @var array
     */
    protected $casts = [];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];
}
