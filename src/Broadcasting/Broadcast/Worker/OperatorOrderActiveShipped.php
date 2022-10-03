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
 * Class OperatorOrderActiveShipped
 * @package Src\Broadcasting\Broadcast\WorkerWeb
 */
class OperatorOrderActiveShipped implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var
     */
    public $shipped;

    /**
     * @var
     */
    protected $worker_id;

    /**
     * @var
     */
    protected $franchise_id;

    /**
     * DispatcherOrderActiveShipped constructor.
     * @param $worker_id
     * @param $franchise_id
     * @param $shipped
     */
    public function __construct($worker_id, $franchise_id, $shipped)
    {
        $this->worker_id = $worker_id;
        $this->franchise_id = $franchise_id;
        $this->shipped = $shipped;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel(ConstChannels::worker_web()->getValue()."-worker-operator.$this->worker_id.$this->franchise_id");
    }
}
