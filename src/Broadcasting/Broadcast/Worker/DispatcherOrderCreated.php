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

/**
 * Class DispatcherOrderCreated
 * @package Src\Broadcasting\Broadcast\WorkerWeb
 */
class DispatcherOrderCreated implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * DispatcherOrderCreated constructor.
     * @param $workerId
     * @param $franchiseId
     * @param $order
     */
    public function __construct(protected $workerId, protected $franchiseId, public $order)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PresenceChannel
     */
    public function broadcastOn(): Channel|PresenceChannel
    {
        return new PresenceChannel(ConstChannels::worker_web()->getValue()."-worker-dispatcher.$this->workerId.$this->franchiseId");
    }
}
