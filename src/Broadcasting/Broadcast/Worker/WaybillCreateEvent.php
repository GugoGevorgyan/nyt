<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Worker;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Enums\ConstChannels;
use Src\Http\Resources\Worker\Waybill\WaybillData;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class WaybillCreateEvent
 * @package Src\Broadcasting\Broadcast\WorkerWeb
 */
class WaybillCreateEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var WaybillData
     */
    public WaybillData $payload;
    /**
     * @var SystemWorker
     */
    protected SystemWorker $worker;

    /**
     * Create a new event instance.
     *
     * @param  SystemWorker  $worker
     * @param  WaybillData  $payload
     */
    public function __construct(SystemWorker $worker, WaybillData $payload)
    {
        $this->worker = $worker;
        $this->payload = $payload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PresenceChannel(ConstChannels::worker_web()->getValue()."-worker.{$this->worker->system_worker_id}.{$this->worker->franchise_id}")
        ];
    }
}
