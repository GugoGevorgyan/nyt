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
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class DriverOrderLate
 * @package Src\Broadcasting\Broadcast\WorkerWeb
 */
class DriverOrderLate implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var array
     */
    public array $payload;
    /**
     * @var SystemWorker
     */
    protected SystemWorker $worker;

    /**
     * Create a new event instance.
     *
     * @param  SystemWorker  $worker
     * @param  array  $payload
     */
    public function __construct(SystemWorker $worker, array $payload)
    {
        $this->worker = $worker;
        $this->payload = $payload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel(ConstChannels::worker_web()->getValue()."-worker-operator.{$this->worker->system_worker_id}.{$this->worker->franchise_id}"),
            new PresenceChannel(ConstChannels::worker_web()->getValue()."-worker-dispatcher.{$this->worker->system_worker_id}.{$this->worker->franchise_id}"),
        ];
    }
}
