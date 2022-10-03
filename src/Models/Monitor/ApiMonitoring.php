<?php

declare(strict_types=1);

namespace Src\Models\Monitor;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class ApiMonitoringListen
 *
 * @package Src\Models
 * @property int $api_monitoring_id
 * @property string|null $api
 * @property string|null $request
 * @property int|null $error
 * @property int|null $response_code
 * @property Carbon|null $created_at
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|Address newModelQuery()
 * @method static Builder|Address newQuery()
 * @method static Builder|Address query()
 * @method static Builder|Address whereApi($value)
 * @method static Builder|Address whereApiMonitoringId($value)
 * @method static Builder|Address whereCreatedAt($value)
 * @method static Builder|Address whereError($value)
 * @method static Builder|Address whereRequest($value)
 * @method static Builder|Address whereResponseCode($value)
 * @mixin Eloquent
 * @property string $request_method
 * @property string|null $updated_at
 * @method static Builder|ApiMonitoring whereRequestMethod($value)
 * @method static Builder|ApiMonitoring whereUpdatedAt($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 * @property int|null $api_key_id
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ApiMonitoring whereApiKeyId($value)
 */
class ApiMonitoring extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'api_monitoring';
    /**
     * @var string
     */
    protected $primaryKey = 'api_monitoring_id';
    /**
     * @var string[]
     */
    protected $fillable = ['api', 'request', 'request_method', 'error', 'response_code'];
    /**
     * @var string[]
     */
    protected $casts = ['created_at' => 'timestamp', 'error' => 'bool'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];
}
