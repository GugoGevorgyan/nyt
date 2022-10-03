<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Worker;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Enums\ConstChannels;
use Src\Http\Resources\Worker\Bookkeeping\BookkeepingData;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class BookkeepingCreateTransaction
 * @package Src\Broadcasting\Broadcast\Worker
 */
class BookkeepingCreateTransaction implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var SystemWorker
     */
    protected SystemWorker $worker;
    /**
     * @var BookkeepingData
     */
    public BookkeepingData $payload;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SystemWorker $worker, BookkeepingData $payload)
    {
        $this->worker = $worker;
        $this->payload = $payload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new PresenceChannel(ConstChannels::worker_web()->getValue()."-worker.{$this->worker->system_worker_id}.{$this->worker->franchise_id}");
    }
}
