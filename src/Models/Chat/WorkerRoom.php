<?php

namespace Src\Models\Chat;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Chat\WorkerRoom
 *
 * @property int $worker_room_id
 * @property int $room_id
 * @property int $system_worker_id
 * @property int $archived
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|WorkerRoom newModelQuery()
 * @method static Builder|WorkerRoom newQuery()
 * @method static Builder|WorkerRoom query()
 * @method static Builder|WorkerRoom whereArchived($value)
 * @method static Builder|WorkerRoom whereCreatedAt($value)
 * @method static Builder|WorkerRoom whereRoomId($value)
 * @method static Builder|WorkerRoom whereSystemWorkerId($value)
 * @method static Builder|WorkerRoom whereUpdatedAt($value)
 * @method static Builder|WorkerRoom whereWorkerRoomId($value)
 * @mixin Eloquent
 */
class WorkerRoom extends ServiceModel
{

}
