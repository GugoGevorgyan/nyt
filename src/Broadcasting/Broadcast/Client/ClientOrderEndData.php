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
 * Class ClientOrderEndData
 * @package Src\Broadcasting\Broadcast
 */
class ClientOrderEndData implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var
     */
    public $price;
    /**
     * @var
     */
    public $currency;
    /**
     * @var
     */
    public $distance;
    /**
     * @var
     */
    public $duration;
    /**
     * @var
     */
    public $orderId;
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * Create a new event instance.
     *
     * @param $client
     * @param $price
     * @param $currency
     * @param $distance
     * @param $duration
     * @param $order_id
     */
    public function __construct($client, $order_id, $price, $currency, $distance, $duration)
    {
        $this->client = $client;

        $this->price = $price;
        $this->currency = $currency;
        $this->distance = $distance;
        $this->duration = $duration;
        $this->orderId = $order_id;
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
