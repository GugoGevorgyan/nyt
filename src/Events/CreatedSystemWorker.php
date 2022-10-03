<?php

declare(strict_types=1);

namespace Src\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class CreatedSystemWorker
 * @package Src\Events
 */
class CreatedSystemWorker
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var
     */
    public $name;

    /**
     * @var
     */
    public $nickname;

    /**
     * @var
     */
    public $password;

    /**
     * @var
     */
    public $email;

    /**
     * Create a new event instance.
     *
     * @param $name
     * @param $nickname
     * @param $password
     * @param $email
     */
    public function __construct($name, $nickname, $password, $email)
    {
        $this->nickname = $nickname;
        $this->password = $password;
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('channel-name');
    }
}
