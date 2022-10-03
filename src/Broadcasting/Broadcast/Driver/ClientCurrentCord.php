<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Driver;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Enums\ConstChannels;
use Src\Models\Driver\Driver;

/**
 * Class ClientCurrentCord
 * @package Src\Broadcasting\Broadcast\Driver
 */
class ClientCurrentCord implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var Driver
     */
    protected Driver $driver;
    /**
     * @var bool
     */
    protected bool $show;
    /**
     * @var array
     */
    protected array $cord;

    /**
     * Create a new event instance.
     *
     * @param  Driver  $driver
     * @param  bool  $show
     * @param  array  $cord
     */
    public function __construct(Driver $driver, array $cord, bool $show = false)
    {
        $this->driver = $driver;
        $this->show = $show;
        $this->cord = $cord;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel(
            ConstChannels::driver_api()->getValue()."-base-driver-channel.{$this->driver->driver_id}.{$this->driver->phone}.{$this->driver->car_id}.{$this->driver->current_franchise_id}"
        );
    }
}
