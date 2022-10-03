<?php

declare(strict_types=1);

namespace Src\Models\Corporate;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ServiceEntity\Models\ServiceModel;

/**
 * Class CompanyReport
 *
 * @package Src\Models\Corporate
 * @property int $company_report_id
 * @property int $company_id
 * @property string $excel
 * @property string $path
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Src\Models\Corporate\Company $company
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport whereCompanyReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport whereExcel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class CompanyReport extends ServiceModel
{


    /**
     * @var string
     */
    protected $table = 'company_reports';
    /**
     * @var string
     */
    protected $primaryKey = 'company_report_id';
    /**
     * @var string[]
     */
    protected $fillable = ['company_id', 'excel', 'disk', 'path', 'name'];

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
