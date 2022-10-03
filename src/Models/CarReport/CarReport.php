<?php

declare(strict_types=1);

namespace Src\Models\CarReport;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Terminal\Waybill;

/**
 * Src\Models\CarReport\CarReport
 *
 * @property int $car_report_id
 * @property int $waybill_id
 * @property int $emergency_lights
 * @property string|null $emergency_lights_comment
 * @property int $headlights
 * @property string|null $headlights_comment
 * @property int $tires
 * @property string|null $tires_comment
 * @property int $engine
 * @property string|null $engine_comment
 * @property array $images
 * @property string $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $unix_date
 * @method static Builder|CarReport newModelQuery()
 * @method static Builder|CarReport newQuery()
 * @method static Builder|CarReport query()
 * @method static Builder|CarReport whereCarReportId($value)
 * @method static Builder|CarReport whereComment($value)
 * @method static Builder|CarReport whereCreatedAt($value)
 * @method static Builder|CarReport whereEmergencyLights($value)
 * @method static Builder|CarReport whereEmergencyLightsComment($value)
 * @method static Builder|CarReport whereEngine($value)
 * @method static Builder|CarReport whereEngineComment($value)
 * @method static Builder|CarReport whereHeadlights($value)
 * @method static Builder|CarReport whereHeadlightsComment($value)
 * @method static Builder|CarReport whereImages($value)
 * @method static Builder|CarReport whereTires($value)
 * @method static Builder|CarReport whereTiresComment($value)
 * @method static Builder|CarReport whereUnixDate($value)
 * @method static Builder|CarReport whereUpdatedAt($value)
 * @method static Builder|CarReport whereWaybillId($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $question_id
 * @property int $verified
 * @property-read int|null $images_count
 * @property-read Waybill $waybill
 * @method static Builder|CarReport whereQuestionId($value)
 * @method static Builder|CarReport whereVerified($value)
 * @property-read \Src\Models\CarReport\CarReportQuestion $question
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
class CarReport extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'car_reports';
    /**
     * @var string
     */
    protected $primaryKey = 'car_report_id';
    /**
     * @var array
     */
    protected $fillable = [
        'waybill_id',
        'question_id',
        'verified',
        'comment',
    ];
    protected $dates = ['created_at'];

    /**
     * @return BelongsTo
     */
    public function waybill(): BelongsTo
    {
        return $this->belongsTo(Waybill::class, 'waybill_id');
    }

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(CarReportImage::class,'report_id',$this->primaryKey);
    }

    /**
     * @return BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(CarReportQuestion::class,'question_id');
    }
}
