<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Worker;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Src\Core\Contracts\Broadcast;
use Src\Core\Enums\ConstChannels;

/**
 *
 */
abstract class BaseWorkerBroadcast implements Broadcast
{
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel|array
     */
    public function broadcastOn(): Channel|PrivateChannel|array
    {
        $worker = $this->worker;
        $channel = '-worker-dispatcher';

        return new PresenceChannel(ConstChannels::worker_web()->getValue()."$channel.$worker->system_worker_id.$worker->franchise_id");
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return static::class;
    }
}
