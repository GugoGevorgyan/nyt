<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Worker;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Enums\ConstChannels;

/**
 * Class WorkerOperatorDriver
 * @package Src\Broadcasting\Broadcast\WorkerWeb
 */
class OperatorDriverUpdated implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var
     */
    public $driver;
    /**
     * @var
     */
    protected $worker_id;
    /**
     * @var
     */
    protected $franchise_id;

    /**
     * WorkerOperatorOrder constructor.
     * @param $worker_id
     * @param $franchise_id
     * @param $driver
     */
    public function __construct($worker_id, $franchise_id, $driver)
    {
        $this->worker_id = $worker_id;
        $this->franchise_id = $franchise_id;
        $this->driver = $driver;
    }

    /**
     * @return Channel|Channel[]|PrivateChannel
     */
    public function broadcastOn()
    {
        return new PresenceChannel(ConstChannels::worker_web()->getValue()."-worker-operator.$this->worker_id.$this->franchise_id");
    }
}
