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
use Src\Models\Client\Client;

/**
 * Class OrderReset
 * @package Src\Broadcasting\Broadcast\ClientWeb
 */
class OrderReset implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  Client  $client
     * @param  array  $payload
     */
    public function __construct(protected Client $client, public array$payload)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(ConstChannels::client_api()->getValue()."-base.{$this->client->client_id}.{$this->client->phone}"),
            new PresenceChannel(ConstChannels::client_web()->getValue()."-base.{$this->client->client_id}.{$this->client->phone}"),
        ];
    }
}
