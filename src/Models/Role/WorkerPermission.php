<?php

declare(strict_types=1);

namespace Src\Models\Role;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class WorkerPermission
 *
 * @package Src\Models\Role
 * @property int $worker_permission_id
 * @property int $worker_id
 * @property int $permission_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Permission|null $permission
 * @property-read SystemWorker $worker
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|WorkerPermission newModelQuery()
 * @method static Builder|WorkerPermission newQuery()
 * @method static Builder|WorkerPermission query()
 * @method static Builder|WorkerPermission whereCreatedAt($value)
 * @method static Builder|WorkerPermission wherePermissionId($value)
 * @method static Builder|WorkerPermission whereUpdatedAt($value)
 * @method static Builder|WorkerPermission whereWorkerId($value)
 * @method static Builder|WorkerPermission whereWorkerPermissionId($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $worker_role_id
 * @method static Builder|WorkerPermission whereWorkerRoleId($value)
 * @property int $system_worker_id
 * @method static Builder|WorkerPermission whereSystemWorkerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class WorkerPermission extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'worker_permission';
    /**
     * @var string
     */
    protected $primaryKey = 'worker_permission_id';
    /**
     * @var array
     */
    protected $fillable = ['permission_id', 'system_worker_id'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     * @return HasOne
     */
    public function permission(): HasOne
    {
        return $this->hasOne(Permission::class, 'permission_id', 'permission_id');
    }

    /**
     * @return BelongsTo
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class);
    }
}
