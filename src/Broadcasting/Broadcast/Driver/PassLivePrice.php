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
 * Class PassLivePrice
 * @package Src\Broadcasting\Broadcast
 * @method broadcast(Driver $driver, $price, $distance, $pause_time, $travel_time)
 */
class PassLivePrice implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  Driver  $driver
     * @param $price
     * @param $distance
     * @param $pause
     * @param $travel_time
     */
    public function __construct(protected Driver $driver, public $price, public $distance, public $pause, public $travel_time)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel
     */
    public function broadcastOn(): Channel|PrivateChannel
    {
        return new PrivateChannel(
            ConstChannels::driver_api()->getValue().
            "-base-driver-channel.{$this->driver->driver_id}.{$this->driver->phone}.{$this->driver->car_id}.{$this->driver->current_franchise_id}"
        );
    }
}
