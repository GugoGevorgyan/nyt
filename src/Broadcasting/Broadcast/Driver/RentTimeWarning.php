<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Driver;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Enums\ConstChannels;
use Src\Models\Driver\Driver;

/**
 *
 */
class RentTimeWarning implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  Driver  $driver
     * @param  array  $payload
     */
    public function __construct(protected Driver $driver, public array $payload)
    {
    }

    public function broadcastOn(): PrivateChannel
    {
        $channel = ConstChannels::driver_api()->getValue().'-base-driver-channel';

        return new PrivateChannel("$channel.{$this->driver->driver_id}.{$this->driver->phone}.{$this->driver->car_id}.{$this->driver->current_franchise_id}");
    }
}
