<?php
declare(strict_types=1);

namespace Src\Models\Views;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class EmailTemplate
 *
 * @package Src\Models
 * @property int $email_template_id
 * @property int|null $type
 * @property string|null $subject
 * @property string|null $body
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|EmailTemplate newModelQuery()
 * @method static Builder|EmailTemplate newQuery()
 * @method static Builder|EmailTemplate query()
 * @method static Builder|EmailTemplate whereBody($value)
 * @method static Builder|EmailTemplate whereCreatedAt($value)
 * @method static Builder|EmailTemplate whereDescription($value)
 * @method static Builder|EmailTemplate whereEmailTemplateId($value)
 * @method static Builder|EmailTemplate whereSubject($value)
 * @method static Builder|EmailTemplate whereType($value)
 * @method static Builder|EmailTemplate whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
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
class EmailTemplate extends ServiceModel
{
    public const CLIENT_REGISTER_CODE_EMAIL = 1;
    public const SYSTEM_WORKER_CREATED_ACCOUNT_DATA = 2;

    public const INSURANCE_DAYS_LEFT_PARK_ADMIN = 3;
    public const INSURANCE_EXPIRED_PARK_ADMIN = 4;
    public const INSPECTION_DAYS_LEFT_PARK_ADMIN = 5;
    public const INSPECTION_EXPIRED_PARK_ADMIN = 6;
    public const INSPECTION_DAYS_LEFT_DRIVER = 7;
    public const INSURANCE_DAYS_LEFT_DRIVER = 8;
    public const INSPECTION_EXPIRED_DRIVER = 9;
    public const INSURANCE_EXPIRED_DRIVER = 10;

    public const MECHANIC_INFORMATION_UPDATE = 11;


    /**
     * @var string
     */
    protected $table = 'email_templates';

    /**
     * @var string
     */
    protected $primaryKey = 'email_template_id';

    /**
     * @var array
     */
    protected $fillable = ['type', 'subject', 'body', 'description'];
}
