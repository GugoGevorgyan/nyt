<?php

declare(strict_types=1);

namespace Src\Core\Contracts;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

interface Broadcast extends ShouldBroadcastNow
{
    /**
     * @return string
     */
    public function broadcastAs(): string;

    /**
     * @return array
     */
    public function broadcastWith(): array;

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array|Channel|string
     */
    public function broadcastOn(): array|Channel|string;
}
