<?php

declare(strict_types=1);

namespace Src\Models\SystemUsers;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Franchise\FranchiseSubPhone;

/**
 * Src\Models\SystemUsers\WorkerOperator
 *
 * @property int $worker_operator_id
 * @property int $system_worker_id
 * @property int $franchise_sub_phone_id
 * @property int $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|WorkerOperator newModelQuery()
 * @method static Builder|WorkerOperator newQuery()
 * @method static Builder|WorkerOperator query()
 * @method static Builder|WorkerOperator whereActive($value)
 * @method static Builder|WorkerOperator whereCreatedAt($value)
 * @method static Builder|WorkerOperator whereFranchiseSubPhoneId($value)
 * @method static Builder|WorkerOperator whereSystemWorkerId($value)
 * @method static Builder|WorkerOperator whereUpdatedAt($value)
 * @method static Builder|WorkerOperator whereWorkerOperatorId($value)
 * @mixin Eloquent
 * @property int $atc_logged
 * @property-read FranchiseSubPhone $sub_phone
 * @property-read SystemWorker $system_worker
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|WorkerOperator whereAtcLogged($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class WorkerOperator extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'worker_operators';
    /**
     * @var string
     */
    protected $primaryKey = 'worker_operator_id';
    /**
     * @var string[]
     */
    protected $fillable = ['system_worker_id', 'franchise_sub_phone_id', 'atc_logged', 'in_call'];
    /**
     * @var string[]
     */
    protected $casts = ['atc_logged' => 'bool'];
    /**
     * @var string
     */
    protected string $map = 'workerOperator';

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
