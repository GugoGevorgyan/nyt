<?php

declare(strict_types=1);

namespace Src\Models\Complaint;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Order\Order;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Src\Models\Complaint
 *
 * @property int $complaint_id
 * @property int $writer_id Кто написал жалобу
 * @property int $recipient_id На кого написали жалобу
 * @property int $status_id На каком этапе расмотрения жалоба
 * @property string $complaint Жалоба
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SystemWorker $recipient
 * @property-read ComplaintStatus $status
 * @property-read SystemWorker $writer
 * @method static Builder|Complaint newModelQuery()
 * @method static Builder|Complaint newQuery()
 * @method static Builder|Complaint query()
 * @method static Builder|Complaint whereComplaint($value)
 * @method static Builder|Complaint whereComplaintId($value)
 * @method static Builder|Complaint whereCreatedAt($value)
 * @method static Builder|Complaint whereRecipientId($value)
 * @method static Builder|Complaint whereStatusId($value)
 * @method static Builder|Complaint whereUpdatedAt($value)
 * @method static Builder|Complaint whereWriterId($value)
 * @mixin Eloquent
 * @property int|null $order_id К какому заказу относится жалоба
 * @property string $decision Отчет по жалобе
 * @property-read Order $order
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|Complaint whereDecision($value)
 * @method static Builder|Complaint whereOrderId($value)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $franchise_id
 * @property string $subject
 * @property-read \Illuminate\Database\Eloquent\Collection|\Src\Models\Complaint\ComplaintFile[] $files
 * @property-read int|null $files_count
 * @method static Builder|Complaint whereFranchiseId($value)
 * @method static Builder|Complaint whereSubject($value)
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
class Complaint extends ServiceModel
{
    /**
     *
     */
    public const COMPLAINT_STATUS_NEW = 1;
    /**
     *
     */
    public const COMPLAINT_STATUS_DURING = 2;
    /**
     *
     */
    public const COMPLAINT_STATUS_ACCEPTED = 3;
    /**
     * @var string
     */
    protected $table = 'complaints';
    /**
     * @var string
     */
    protected $primaryKey = 'complaint_id';
    /**
     * @var array
     */
    protected $fillable = ['franchise_id', 'order_id', 'writer_id', 'recipient_id', 'status_id', 'subject', 'complaint'];


    /**
     * @return HasOne
     */
    public function writer(): HasOne
    {
        return $this->hasOne(SystemWorker::class, 'system_worker_id', 'writer_id');
    }

    /**
     * @return HasOne
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'order_id', 'order_id');
    }

    /**
     * @return HasOne
     */
    public function recipient(): HasOne
    {
        return $this->hasOne(SystemWorker::class, 'system_worker_id', 'recipient_id');
    }

    /**
     * @return HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(ComplaintStatus::class, 'complaint_status_id', 'status_id');
    }

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(ComplaintFile::class, 'complaint_id', 'complaint_id');
    }
}
