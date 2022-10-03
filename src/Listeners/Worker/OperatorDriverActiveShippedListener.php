<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;


use Illuminate\Support\Collection;
use Src\Broadcasting\Broadcast\Worker\OperatorDriverActiveShipped;
use Src\Core\Enums\ConstQueue;
use Src\Events\Order\OrderShippedDriverEvent;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;

/**
 * Class OperatorDriverActiveShippedListener
 * @package Src\Listeners\Worker
 */
class OperatorDriverActiveShippedListener
{
    /**
     * OperatorDriverActiveShippedListener constructor.
     */
    public function __construct(
        protected DriverContract $driverContract,
        protected SystemWorkerContract $workerContract,
        protected OrderShippedDriverContract $shippedContract
    ) {
    }

    /**
     * @param  OrderShippedDriverEvent  $event
     */
    public function handle(OrderShippedDriverEvent $event): void
    {
        if (!$event->shipped['shipped_id']) {
            return;
        }

        $shipped = $this->shippedContract->with('status')->find($event->shipped['shipped_id']);
        $driver = $this->driverContract->find($shipped['driver_id']);
        $workers = $this->getListenerWorkers($driver->current_franchise_id);

        $passed_data = ['driver_id' => $shipped['driver_id'], 'shipped' => $shipped['current'] ? $shipped : null];

        foreach ($workers as $worker) {
            OperatorDriverActiveShipped::broadcast($worker->system_worker_id, $worker->franchise_id, $passed_data);
        }
    }

    /**
     * @param $franchise_id
     * @return array|Collection
     */
    public function getListenerWorkers($franchise_id): array|Collection
    {
        return $this->workerContract
            ->whereHas('worker_operator')
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
