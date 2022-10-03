<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Worker;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Enums\ConstChannels;
use Src\Models\Client\Client;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class ClientLessonRadiusTaxiEvent
 * @package Src\Broadcasting\Broadcast
 * @method serializeData($data)
 */
class ListenRadiusTaxiEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  object  $client
     * @param  object|null  $taxis
     * @param  string  $status
     */
    public function __construct(protected object $client, public object|null $taxis, public string $status = '')
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel(ConstChannels::worker_web()->getValue()."-worker-dispatcher.{$this->client->system_worker_id}.{$this->client->franchise_id}"),
            new PresenceChannel(ConstChannels::worker_web()->getValue()."-worker-operator.{$this->client->system_worker_id}.{$this->client->franchise_id}"),
        ];
    }
}
