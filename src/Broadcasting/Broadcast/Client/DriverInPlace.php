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

/**
 * Class DriverInPlace
 * @package Src\Broadcasting\Broadcast
 */
class DriverInPlace implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var array
     */
    public $payload;
    /**
     * @var
     */
    protected $client;

    /**
     * @var
     */
    public $message;

    /**
     * @var
     */
    public $tariff_features;

    /**
     * Create a new event instance.
     *
     * @param $client
     * @param $payload
     * @param $message
     * @param $tariff_features
     */
    public function __construct($client, $payload, $message, $tariff_features)
    {
        $this->client = $client;
        $this->payload = $payload;
        $this->message = $message;
        $this->tariff_features = $tariff_features;
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
