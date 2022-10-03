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
 * Class ClientPassOrderPrice
 * @package Src\Broadcasting\Broadcast
 */
class ClientPassOrderPrice implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var float|null
     */
    public ?float $coin = null;
    /**
     * @var float|null
     */
    public ?float $sitting_coin = null;
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * Create a new event instance.
     *
     * @param  Client  $client
     * @param  float|null  $price
     * @param  float|null  $sitting_price
     */
    public function __construct(Client $client, ?float $price = null, ?float $sitting_price = null)
    {
        $this->client = $client;
        $this->coin = $price;
        $this->sitting_coin = $sitting_price;
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
