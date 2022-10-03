<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Client;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Enums\ConstChannels;

/**
 * Class SearchDriverTimeoutEvent
 * @package Src\Events\Order
 */
class SearchDriverTimeoutEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var
     */
    public $clientId;
    /**
     * @var
     */
    public $orderId;
    /**
     * @var
     */
    protected $clientPhone;
    /**
     * @var
     */
    protected $mobile;

    /**
     * Create a new event instance.
     *
     * @param $client_id
     * @param $client_phone
     * @param $mobile
     * @param $order_id
     */
    public function __construct($client_id, $client_phone, $order_id, $mobile)
    {
        $this->clientId = $client_id;
        $this->orderId = $order_id;
        $this->clientPhone = $client_phone;
        $this->mobile = $mobile;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PresenceChannel
     */
    public function broadcastOn(): Channel|PresenceChannel
    {
        return new PresenceChannel(
            $this->mobile ? ConstChannels::client_api()->getValue() : ConstChannels::client_web()->getValue()."-base.{$this->clientId}.{$this->clientPhone}"
        );
    }
}
