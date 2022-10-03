<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;

use Illuminate\Support\Collection;
use Src\Broadcasting\Broadcast\Worker\DispatcherCallUpdated;
use Src\Core\Enums\ConstQueue;
use Src\Repositories\SystemWorker\SystemWorkerContract;

/**
 * Class DispatcherClientCallUpdateListener
 * @package Src\Listeners\Worker
 */
class DispatcherClientCallUpdateListener
{
    /**
     * DispatcherClientCallUpdateListener constructor.
     */
    public function __construct(protected SystemWorkerContract $workerContract)
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(object $event): void
    {
        $clientCall = $event->clientCall->load(['system_worker', 'client', 'franchisePhone', 'franchiseSubPhone']);
        $workers = $this->getListenerWorkers($event->clientCall->franchise_id);

        foreach ($workers as $worker) {
            DispatcherCallUpdated::broadcast($worker->system_worker_id, $worker->franchise_id, $clientCall);
        }
    }

    /**
     * @param $franchise_id
     * @return array|Collection
     */
    public function getListenerWorkers($franchise_id): array|Collection
    {
        return $this->workerContract
            ->whereHas('worker_dispatcher')
            ->where('franchise_id', '=', $franchise_id)
            ->where('in_session', '=', 1)
            ->findAll() ?: [];
    }

    /**
     * Get the name of the listener's queue.
     *
     * @return string
     */
    public function viaQueue(): string
    {
        return ConstQueue::OBSERVER()->getValue();
    }
}
