<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Driver;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Src\Core\Contracts\Broadcast;
use Src\Core\Enums\ConstChannels;

/**
 *
 */
abstract class DriverBaseBroadcast implements Broadcast
{
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel|array
     */
    public function broadcastOn(): Channel|PrivateChannel|array
    {
        $driver = $this->driver;
        $channel = '-base-driver-channel';

        return new PrivateChannel(ConstChannels::driver_api()->getValue()."$channel.$driver->driver_id.$driver->phone.$driver->car_id.$driver->current_franchise_id");
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return static::class;
    }
}
