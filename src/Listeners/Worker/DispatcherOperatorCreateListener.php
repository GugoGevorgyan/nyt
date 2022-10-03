<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;

use Src\Broadcasting\Broadcast\Worker\DispatcherOperatorCreated;
use Src\Core\Enums\ConstQueue;
use Src\Repositories\SystemWorker\SystemWorkerContract;

/**
 * Class DispatcherOperatorCreateListener
 * @package Src\Listeners\Worker
 */
class DispatcherOperatorCreateListener
{
    /**
     * DispatcherOperatorCreateListener constructor.
     */
    public function __construct(protected SystemWorkerContract $workerContract)
    {
    }

    /**
     * @param $event
     */
    public function handle($event): void
    {
        $worker = $this->workerContract->with('worker_operator.sub_phone')->find($event->operator->system_worker_id);

        $dispatchers = $this->workerContract
            ->where('franchise_id', '=', $worker->franchise_id)
            ->whereHas('worker_dispatcher')
            ->findAll();

        foreach ($dispatchers as $dispatcher) {
            DispatcherOperatorCreated::broadcast($dispatcher->system_worker_id, $worker->franchise_id, $worker);
        }
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
