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
 * Class SpeedLimitWarning
 * @package Src\Broadcasting\Broadcast\Driver
 */
class SpeedLimitWarning implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var Driver
     */
    protected $driver;
    /**
     * @var int
     */
    protected $speed;

    /**
     * Create a new event instance.
     *
     * @param $driver
     * @param $speed
     */
    public function __construct($driver, $speed)
    {
        $this->driver = $driver;
        $this->speed = $speed;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        $channel = ConstChannels::driver_api()->getValue().'-base-driver-channel';

        return new PrivateChannel("$channel.{$this->driver->driver_id}.{$this->driver->phone}.{$this->driver->car_id}.{$this->driver->current_franchise_id}");
    }
}
