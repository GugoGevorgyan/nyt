<?php

declare(strict_types=1);

namespace Src\Models\SystemUsers;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class WorkerGraphic
 *
 * @package Src\Models
 * @property int $worker_graphic_id
 * @property int $work_days_count
 * @property string $work_days
 * @property int $weekend_days_count
 * @property string $weekend_days enum('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
 * @property array $opening_hours
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|SystemWorker[] $workers
 * @property-read int|null $workers_count
 * @method static Builder|WorkerGraphic newModelQuery()
 * @method static Builder|WorkerGraphic newQuery()
 * @method static Builder|WorkerGraphic query()
 * @method static Builder|WorkerGraphic whereCreatedAt($value)
 * @method static Builder|WorkerGraphic whereOpeningHours($value)
 * @method static Builder|WorkerGraphic whereUpdatedAt($value)
 * @method static Builder|WorkerGraphic whereWeekendDays($value)
 * @method static Builder|WorkerGraphic whereWeekendDaysCount($value)
 * @method static Builder|WorkerGraphic whereWorkDays($value)
 * @method static Builder|WorkerGraphic whereWorkDaysCount($value)
 * @method static Builder|WorkerGraphic whereWorkerGraphicId($value)
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
class WorkerGraphic extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'workers_graphic';

    /**
     * @var string
     */
    protected $primaryKey = 'worker_graphic_id';

    /**
     * @var array
     */
    protected $casts = ['opening_hours' => 'json'];

    /**
     * @var array
     */
    protected $fillable = [
        'worker_graphic_id',
        'work_days_count',
        'work_days',
        'weekend_days_count',
        'weekend_days',
        'opening_hours'
    ];

    /**
     * @return HasMany
     */
    public function workers(): HasMany
    {
        return $this->hasMany(SystemWorker::class, 'schedule_id');
    }
}
