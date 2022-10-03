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
 * Class PassScheduleAcceptedEvent
 * @package Src\Broadcasting\Broadcast
 */
class PassScheduleAcceptedEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $driver;
    /**
     * @var int
     */
    protected $clientId;
    /**
     * @var int
     */
    protected $phone;

    /**
     * Create a new event instance.
     *
     * @param $client_id
     * @param $phone
     * @param $driver
     */
    public function __construct($client_id, $phone, $driver)
    {
        $this->clientId = $client_id;
        $this->phone = $phone;

        $this->driver = $driver;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PresenceChannel(ConstChannels::client_web()->getValue()."-base.{$this->clientId}.{$this->phone}"),
            new PrivateChannel(ConstChannels::client_api()->getValue()."-base.{$this->clientId}.{$this->phone}"),
        ];
    }
}
