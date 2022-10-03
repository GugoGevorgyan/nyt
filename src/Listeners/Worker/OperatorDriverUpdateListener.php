<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;


use Illuminate\Support\Collection;
use Src\Broadcasting\Broadcast\Worker\OperatorDriverUpdated;
use Src\Core\Enums\ConstQueue;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;

/**
 * Class OperatorDriverUpdateListener
 * @package Src\Listeners\Worker
 */
class OperatorDriverUpdateListener
{
    /**
     * OperatorDriverUpdateListener constructor.
     * @param  DriverContract  $driverContract
     * @param  SystemWorkerContract  $workerContract
     */
    public function __construct(protected DriverContract $driverContract, protected SystemWorkerContract $workerContract)
    {
    }

    /**
     * @param $event
     */
    public function handle($event): void
    {
        $driver = $event->driver->load(['status', 'driver_info', 'active_order_shipment', 'car.classes', 'car.park']);
        $workers = $this->getListenerWorkers($driver->current_franchise_id);

        foreach ($workers as $worker) {
            OperatorDriverUpdated::broadcast($worker->system_worker_id, $driver->current_franchise_id, $driver);
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
            ->findAll(['system_worker_id', 'franchise_id']) ?: [];
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
