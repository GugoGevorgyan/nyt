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
 * Class BroadwayDriverTalk
 * @package Src\Broadcasting\Broadcast\ClientWeb
 */
class BroadwayDriverTalk implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var array
     */
    public array $messageData;
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * Create a new event instance.
     *
     * @param  Client  $client
     * @param  array  $msg
     */
    public function __construct(Client $client, array $msg)
    {
        $this->client = $client;
        $this->messageData = $msg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel(ConstChannels::client_api()->getValue()."-base.{$this->client->client_id}.{$this->client->phone}"),
            new PresenceChannel(ConstChannels::client_web()->getValue()."-base.{$this->client->client_id}.{$this->client->phone}"),
        ];
    }
}
