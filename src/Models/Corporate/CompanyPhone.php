<?php

namespace Src\Models\Corporate;

use Eloquent;
use Grimzy\LaravelMysqlSpatial\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Src\Models\Corporate\CompanyPhone
 *
 * @property int $company_phone_id
 * @property int|null $company_id
 * @property string $number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereCompanyPhoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereUpdatedAt($value)
 * @mixin Eloquent
 * @property Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Query\Builder|CompanyPhone onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|CompanyPhone withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CompanyPhone withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel comparison($geometryColumn, $geometry, $relationship)
 * @method static Builder|ServiceModel contains($geometryColumn, $geometry)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel crosses($geometryColumn, $geometry)
 * @method static Builder|ServiceModel disjoint($geometryColumn, $geometry)
 * @method static Builder|ServiceModel distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static Builder|ServiceModel distanceSphere($geometryColumn, $geometry, $distance)
 * @method static Builder|ServiceModel distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static Builder|ServiceModel distanceSphereValue($geometryColumn, $geometry)
 * @method static Builder|ServiceModel distanceValue($geometryColumn, $geometry)
 * @method static Builder|ServiceModel doesTouch($geometryColumn, $geometry)
 * @method static Builder|ServiceModel equals($geometryColumn, $geometry)
 * @method static Builder|ServiceModel intersects($geometryColumn, $geometry)
 * @method static Builder|ServiceModel orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static Builder|ServiceModel orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static Builder|ServiceModel orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static Builder|ServiceModel overlaps($geometryColumn, $geometry)
 * @method static Builder|ServiceModel within($geometryColumn, $polygon)
 */
class CompanyPhone extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'company_phones';
    /**
     * @var string
     */
    protected $primaryKey = 'company_phone_id';
    /**
     * @var array
     */
    protected $fillable = ['company_id', 'number'];
}
