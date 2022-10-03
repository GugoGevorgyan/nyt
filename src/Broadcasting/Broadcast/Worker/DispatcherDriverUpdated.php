<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Worker;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class WorkerDispatcherDriver
 * @package Src\Broadcasting\Broadcast\WorkerWeb
 */
class DispatcherDriverUpdated extends BaseWorkerBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * WorkerDispatcherDriver constructor.
     * @param  object  $worker
     * @param  object  $driver
     */
    public function __construct(protected object $worker, protected object $driver)
    {
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'driver' => 'object'
    ])]
    public function broadcastWith(): array
    {
        return ['driver' => $this->driver];
    }
}
