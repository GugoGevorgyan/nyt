<?php

declare(strict_types=1);

namespace Src\Models\SystemUsers;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Franchise\FranchiseSubPhone;
use Src\Scopes\WorkerDispatcherScope;

/**
 * Src\Models\SystemUsers\WorkerDispatcher
 *
 * @property int $worker_dispatcher_id
 * @property int $system_worker_id
 * @property int $franchise_sub_phone_id
 * @property int $atc_logged
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read FranchiseSubPhone $sub_phone
 * @property-read SystemWorker $system_worker
 * @method static Builder|WorkerDispatcher newModelQuery()
 * @method static Builder|WorkerDispatcher newQuery()
 * @method static Builder|WorkerDispatcher query()
 * @method static Builder|WorkerDispatcher whereAtcLogged($value)
 * @method static Builder|WorkerDispatcher whereCreatedAt($value)
 * @method static Builder|WorkerDispatcher whereFranchiseSubPhoneId($value)
 * @method static Builder|WorkerDispatcher whereSystemWorkerId($value)
 * @method static Builder|WorkerDispatcher whereUpdatedAt($value)
 * @method static Builder|WorkerDispatcher whereWorkerDispatcherId($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class WorkerDispatcher extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'worker_dispatchers';
    /**
     * @var string
     */
    protected $primaryKey = 'worker_dispatcher_id';
    /**
     * @var string[]
     */
    protected $fillable = [
        'system_worker_id',
        'franchise_sub_phone_id',
        'atc_logged',
        'in_call'
    ];
    /**
     * @var string[]
     */
    protected $casts = ['atc_logged' => 'bool'];
    /**
     * @var string
     */
    protected string $map = 'workerDispatcher';

    /**
     * @return BelongsTo
     */
    public function system_worker(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class, 'system_worker_id', 'system_worker_id');
    }

    /**
     * @return BelongsTo
     */
    public function sub_phone(): BelongsTo
    {
        return $this->belongsTo(FranchiseSubPhone::class, 'franchise_sub_phone_id', 'franchise_sub_phone_id');
    }
}
