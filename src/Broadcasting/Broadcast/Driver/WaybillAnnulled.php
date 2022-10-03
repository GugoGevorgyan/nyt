<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Driver;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Enums\ConstChannels;
use Src\Models\Driver\Driver;

/**
 * Class WaybillAnnulled
 * @package Src\Broadcasting\Broadcast\Driver
 */
class WaybillAnnulled implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var array
     */
    public array $payload;
    /**
     * @var Driver
     */
    protected Driver $driver;

    /**
     * Create a new event instance.
     *
     * @param  Driver  $driver
     * @param  array  $payload
     */
    public function __construct(Driver $driver, array $payload)
    {
        $this->driver = $driver;
        $this->payload = $payload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new PrivateChannel(ConstChannels::driver_api()->getValue().'-base-driver-channel'.$this->parseAuth());
    }

    /**
     * @return string
     */
    protected function parseAuth(): string
    {
        return '.'.$this->driver->driver_id.'.'.$this->driver->phone.'.'.$this->driver->car_id.'.'.$this->driver->current_franchise_id;
    }
}
