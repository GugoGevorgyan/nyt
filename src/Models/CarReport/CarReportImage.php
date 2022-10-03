<?php

declare(strict_types=1);

namespace Src\Models\CarReport;

use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ServiceEntity\Models\ServiceModel;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class CarReportImage
 *
 * @package Src\Models\CarReport
 * @property int $car_report_image_id
 * @property int $report_id
 * @property string $path
 * @property string $name
 * @property string $ext
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Src\Models\CarReport\CarReport $report
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|CarReportImage newModelQuery()
 * @method static Builder|CarReportImage newQuery()
 * @method static Builder|CarReportImage query()
 * @method static Builder|CarReportImage whereCarReportImageId($value)
 * @method static Builder|CarReportImage whereCreatedAt($value)
 * @method static Builder|CarReportImage whereExt($value)
 * @method static Builder|CarReportImage whereName($value)
 * @method static Builder|CarReportImage wherePath($value)
 * @method static Builder|CarReportImage whereReportId($value)
 * @method static Builder|CarReportImage whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class CarReportImage extends ServiceModel
{


    /**
     * @var string
     */
    protected $table = 'car_report_images';
    /**
     * @var string
     */
    protected $primaryKey = 'car_report_id';
    /**
     * @var array
     */
    protected $fillable = ['report_id', 'path', 'name', 'ext'];

    /**
     * @return BelongsTo
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(CarReport::class, 'report_id', 'car_report_id');
    }
}
