<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Worker;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ReflectionException;
use Src\Core\Enums\ConstChannels;

/**
 * Class SendChatMessageEvent
 * @package Src\Events
 */
class SendChatMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var
     */
    public $message;

    /**
     * @var
     */
    public $sender_id;

    /**
     * @var
     */
    public $room_id;

    /**
     * @var
     */
    public $participant_id;

    /**
     * Create a new event instance.
     *
     * @param $sender_id
     * @param $participant_id
     * @param $room_id
     * @param $message
     */
    public function __construct($sender_id, $participant_id, $room_id, $message)
    {
        $this->sender_id = $sender_id;
        $this->room_id = $room_id;
        $this->participant_id = $participant_id;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PresenceChannel
     */
    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel(ConstChannels::worker_web()->getValue().'-worker-chat.'.$this->participant_id);
    }
}
