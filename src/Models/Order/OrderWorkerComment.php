<?php

namespace Src\Models\Order;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Src\Models\Order\OrderWorkerComment
 *
 * @property int $order_worker_comment_id
 * @property int $order_id
 * @property int $system_worker_id
 * @property string $text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SystemWorker $worker
 * @method static Builder|OrderWorkerComment newModelQuery()
 * @method static Builder|OrderWorkerComment newQuery()
 * @method static Builder|OrderWorkerComment query()
 * @method static Builder|OrderWorkerComment whereCreatedAt($value)
 * @method static Builder|OrderWorkerComment whereOrderId($value)
 * @method static Builder|OrderWorkerComment whereOrderWorkerCommentId($value)
 * @method static Builder|OrderWorkerComment whereSystemWorkerId($value)
 * @method static Builder|OrderWorkerComment whereText($value)
 * @method static Builder|OrderWorkerComment whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $driver
 * @method static Builder|OrderWorkerComment whereDriver($value)
 */
class OrderWorkerComment extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'order_worker_comments';
    /**
     * @var string
     */
    protected $primaryKey = 'order_worker_comment_id';
    /**
     * @var array
     */
    protected $fillable = ['order_id', 'system_worker_id', 'text', 'driver'];

    /**
     * @return BelongsTo
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class, 'system_worker_id', 'system_worker_id');
    }
}
