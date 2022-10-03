<?php

declare(strict_types=1);

namespace Src\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class UpdatedMechanicInfo
 * @package Src\Events
 */
class UpdatedMechanicInfo
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * UpdatedMechanicInfo constructor.
     * @param  string  $name
     * @param  string  $email
     * @param  string  $admin
     */
    public function __construct(public string $name, public string $email, public string $admin)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('channel-name');
    }
}
