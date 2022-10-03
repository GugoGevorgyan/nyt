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
 * Class DispatcherOrderCommon
 * @package Src\Broadcasting\Broadcast\WorkerWeb
 */
class DispatcherOrderCommon implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var
     */
    public $common;

    /**
     * @var
     */
    protected $worker_id;

    /**
     * @var
     */
    protected $franchise_id;

    /**
     * DispatcherOrderCommon constructor.
     * @param $worker_id
     * @param $franchise_id
     * @param $common
     */
    public function __construct($worker_id, $franchise_id, $common)
    {
        $this->worker_id = $worker_id;
        $this->franchise_id = $franchise_id;
        $this->common = $common;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PresenceChannel
     */
    public function broadcastOn(): Channel|PresenceChannel
    {
        return new PresenceChannel(ConstChannels::worker_web()->getValue()."-worker-dispatcher.$this->worker_id.$this->franchise_id");
    }
}
