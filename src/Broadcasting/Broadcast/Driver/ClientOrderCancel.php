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
 * Class ClientOrderCancel
 * @package Src\Broadcasting\Broadcast
 */
class ClientOrderCancel implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var
     */
    public $order;
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
     * @param $order
     * @param  array  $payload
     */
    public function __construct(Driver $driver, $order, array $payload = [])
    {
        $this->driver = $driver;
        $this->order = $order;
        $this->payload = $payload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel
     */
    public function broadcastOn(): Channel|PrivateChannel
    {
        return new PrivateChannel(
            ConstChannels::driver_api()->getValue()."-base-driver-channel.{$this->driver->driver_id}.{$this->driver->phone}.{$this->driver->car_id}.{$this->driver->current_franchise_id}"
        );
    }
}
