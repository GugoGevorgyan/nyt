<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Client;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Enums\ConstChannels;
use Src\Models\Client\Client;

/**
 * Class DriverOnAcceptOrderEvent
 * @package Src\Broadcasting\Broadcast\ClientWeb
 */
class DriverOnAcceptOrderEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var object
     */
    public object $payload;
    /**
     * @var string
     */
    public string $hash;
    /**
     * @var Client
     */
    protected Client $client;
    /**
     * @var string
     */
    public string $message;

    /**
     * Create a new event instance.
     *
     * @param  Client  $client
     * @param  object  $driver
     * @param  string  $hash
     * @param  string  $message
     */
    public function __construct(Client $client, object $driver, string $hash, string $message)
    {
        $this->client = $client;
        $this->payload = $driver;
        $this->hash = $hash;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PresenceChannel(ConstChannels::client_web()->getValue()."-base.{$this->client->client_id}.{$this->client->phone}"),
            new PrivateChannel(ConstChannels::client_api()->getValue()."-base.{$this->client->client_id}.{$this->client->phone}"),
        ];
    }
}
