<?php

declare(strict_types=1);

namespace Src\Models\Role;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class WorkerRole
 *
 * @package Src\Models
 * @property int $worker_role_id
 * @property int|null $system_worker_id
 * @property int|null $role_id
 * @property mixed $permission_ids
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Role|null $role
 * @property-read SystemWorker $worker
 * @method static Builder|WorkerRole newModelQuery()
 * @method static Builder|WorkerRole newQuery()
 * @method static Builder|WorkerRole query()
 * @method static Builder|WorkerRole whereCreatedAt($value)
 * @method static Builder|WorkerRole wherePermissionIds($value)
 * @method static Builder|WorkerRole whereRoleId($value)
 * @method static Builder|WorkerRole whereSystemWorkerId($value)
 * @method static Builder|WorkerRole whereUpdatedAt($value)
 * @method static Builder|WorkerRole whereWorkerRoleId($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|WorkerPermission[] $worker_permissions
 * @property-read int|null $worker_permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class WorkerRole extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'worker_role';
    /**
     * @var string
     */
    protected $primaryKey = 'worker_role_id';
    /**
     * @var array
     */
    protected $fillable = ['role_id', 'system_worker_id'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];
    /**
     * @var array
     */
    protected $casts = [];

    /**
     * @return HasMany
     */
    public function worker_permissions(): HasMany
    {
        return $this->hasMany(WorkerPermission::class, 'worker_role_id', 'worker_role_id');
    }

    /**
     * @return BelongsTo
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class);
    }

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}
