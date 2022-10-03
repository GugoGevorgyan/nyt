<?php

declare(strict_types=1);

namespace Src\Models\SystemWorker;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Src\Models\SystemUsers\SystemWorker;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use ServiceEntity\Models\ServiceModel;

/**
 * Class WorkerSession
 *
 * @package Src\Models\SystemWorker
 * @property int $worker_session_id
 * @property int|null $quit_worker_id
 * @property int|null $logged_worker_id
 * @property string $token
 * @property Carbon|null $quit_time
 * @property Carbon|null $logged_time
 * @property Carbon|null $deleted_at
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|WorkerSession newModelQuery()
 * @method static Builder|WorkerSession newQuery()
 * @method static \Illuminate\Database\Query\Builder|WorkerSession onlyTrashed()
 * @method static Builder|WorkerSession query()
 * @method static Builder|WorkerSession whereDeletedAt($value)
 * @method static Builder|WorkerSession whereLoggedTime($value)
 * @method static Builder|WorkerSession whereLoggedWorkerId($value)
 * @method static Builder|WorkerSession whereQuitTime($value)
 * @method static Builder|WorkerSession whereQuitWorkerId($value)
 * @method static Builder|WorkerSession whereToken($value)
 * @method static Builder|WorkerSession whereWorkerSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|WorkerSession withTrashed()
 * @method static \Illuminate\Database\Query\Builder|WorkerSession withoutTrashed()
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 */
class WorkerSession extends ServiceModel
{
    use SoftDeletes;

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'worker_sessions';
    /**
     * @var string
     */
    protected $primaryKey = 'worker_session_id';
    /**
     * @var string[]
     */
    protected $fillable = ['quit_worker_id', 'logged_worker_id', 'token', 'quit_time', 'logged_time'];
    /**
     * @var string[]
     */
    protected $dates = ['quit_time', 'logged_time'];

    /**
     * @return BelongsTo
     */
    protected function quit_worker(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class, 'quit_worker_id');
    }

    /**
     * @return BelongsTo
     */
    protected function logged_worker(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class, 'logged_worker_id');
    }
}
