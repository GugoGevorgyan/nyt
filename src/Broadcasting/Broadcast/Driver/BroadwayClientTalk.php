<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Driver;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\ArrayShape;
use Src\Models\Driver\Driver;

/**
 * Class BroadwayClientTalk
 * @package Src\Broadcasting\Broadcast\Driver
 */
class BroadwayClientTalk extends DriverBaseBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  Driver  $driver
     * @param  string  $msg
     */
    public function __construct(protected Driver $driver, public string $msg)
    {
    }

    /**
     * @return string[]
     */
    #[ArrayShape([
        'msg' => 'string'
    ])] public function broadcastWith(): array
    {
        return ['msg' => $this->msg];
    }
}
