<?php

declare(strict_types=1);

namespace Src\Models\Driver;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ServiceEntity\Models\ServiceModel;

/**
 * Class DriverGraphic
 *
 * @package Src\Models
 * @property int $driver_graphic_id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $working_days_count
 * @property int|null $weekend_days_count
 * @property-read Collection|Driver[] $drivers
 * @method static Builder|DriverGraphic newModelQuery()
 * @method static Builder|DriverGraphic newQuery()
 * @method static Builder|DriverGraphic query()
 * @method static Builder|DriverGraphic whereDescription($value)
 * @method static Builder|DriverGraphic whereDriverGraphicId($value)
 * @method static Builder|DriverGraphic whereName($value)
 * @method static Builder|DriverGraphic whereWeekendDaysCount($value)
 * @method static Builder|DriverGraphic whereWorkingDaysCount($value)
 * @mixin Eloquent
 * @property int|null $type
 * @method static Builder|DriverGraphic whereType($value)
 * @property mixed $week
 * @property-read int|null $drivers_count
 * @method static Builder|DriverGraphic whereWeek($value)
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
class DriverGraphic extends ServiceModel
{
    public const DRIVER_GRAPHIC_FIVE = 1;
    public const DRIVER_GRAPHIC_SEX = 2;
    public const DRIVER_GRAPHIC_ONE = 3;
    public const DRIVER_GRAPHIC_TWO = 4;

    /**
     * @var string
     */
    protected $table = 'driver_graphics';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_graphic_id';
    /**
     * @var array
     */
    protected $fillable = ['name', 'type', 'description', 'working_days_count', 'weekend_days_count'];
    /**
     * @var string[]
     */
    protected $casts = ['week' => 'json'];

    /**
     * @return HasMany
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'driver_id');
    }
}
