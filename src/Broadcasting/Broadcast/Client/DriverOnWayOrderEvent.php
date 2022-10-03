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
use Src\Http\Resources\Driver\DriverMapViewResource;
use Src\Models\Client\Client;

/**
 * Class DriverOnWayOrderEvent
 * @package Src\Broadcasting\Broadcast
 */
class DriverOnWayOrderEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var DriverMapViewResource
     */
    public DriverMapViewResource $payload;
    /**
     * @var string
     */
    public string $timeline;
    /**
     * @var Client
     */
    protected Client $client;

    public string $message;

    /**
     * Create a new event instance.
     *
     * @param  Client  $client
     * @param  DriverMapViewResource  $payload
     * @param $duration
     * @param  string  $message
     */
    public function __construct(Client $client, DriverMapViewResource $payload, $duration, string $message)
    {
        $this->client = $client;
        $this->payload = $payload;
        $this->timeline = (string)trans('messages.driver_arrival_time', ['minute' => $duration]);
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
            new PrivateChannel(ConstChannels::client_api()->getValue()."-base.{$this->client->client_id}.{$this->client->phone}")
        ];
    }
}
