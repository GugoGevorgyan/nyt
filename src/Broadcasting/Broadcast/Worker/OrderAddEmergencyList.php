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
 * Class OrderAddEmergencyList
 * @package Src\Broadcasting\Broadcast\WorkerWeb
 */
class OrderAddEmergencyList implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var
     */
    public $common_order;
    /**
     * @var
     */
    protected $worker_id;
    /**
     * @var
     */
    protected $franchise_id;

    /**
     * Create a new event instance.
     *
     * @param $worker_id
     * @param $franchise_id
     * @param $common_order
     */
    public function __construct($worker_id, $franchise_id, $common_order)
    {
        $this->worker_id = $worker_id;
        $this->franchise_id = $franchise_id;
        $this->common_order = $common_order;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel|PresenceChannel
    {
        return new PresenceChannel(ConstChannels::worker_web()->getValue()."-worker.$this->worker_id.$this->franchise_id");
    }
}
