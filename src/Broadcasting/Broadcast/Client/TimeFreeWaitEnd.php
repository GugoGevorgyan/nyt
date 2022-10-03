<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Client;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Enums\ConstChannels;
use Src\Models\Driver\Driver;

/**
 * @Called In Queue Jobs
 *
 * Class TimeFreeWaitEnd
 * @package Src\Broadcasting\Broadcast
 */
class TimeFreeWaitEnd implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var string
     */
    public string $message;
    /**
     * @var Driver
     */
    protected Driver $driver;
    /**
     * @var object
     */
    protected object $client;

    /**
     * Create a new event instance.
     *
     * @param  object  $client
     * @param  Driver  $driver
     * @param  string  $message
     */
    public function __construct(object $client, Driver $driver, string $message)
    {
        $this->client = $client;
        $this->driver = $driver;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel(ConstChannels::client_web()->getValue()."-base.{$this->client->client_id}.{$this->client->phone}"),
            new PrivateChannel(ConstChannels::client_api()->getValue()."-base.{$this->client->client_id}.{$this->client->phone}"),
        ];
    }
}
