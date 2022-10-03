<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;

use Illuminate\Support\Collection;
use Src\Broadcasting\Broadcast\Worker\OperatorOrderActiveShipped;
use Src\Core\Enums\ConstQueue;
use Src\Events\Order\OrderShippedDriverEvent;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;

/**
 * Class OperatorOrderActiveShippedListener
 * @package Src\Listeners\Worker
 */
class OperatorOrderActiveShippedListener
{
    /**
     * OperatorDriverActiveShippedListener constructor.
     */
    public function __construct(
        protected OrderContract $orderContract,
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
        $order = $this->orderContract->find($shipped['order_id']);
        $workers = $this->getListenerWorkers($order['franchisee']['ids']);

        $passed_data = ['order_id' => $shipped['order_id'], 'shipped' => $shipped['current'] ? $shipped : null];
        foreach ($workers as $worker) {
            OperatorOrderActiveShipped::broadcast($worker->system_worker_id, $worker->franchise_id, $passed_data);
        }
    }

    /**
     * @param $franchise_ids
     * @return array|Collection
     */
    public function getListenerWorkers($franchise_ids): array|Collection
    {
        return $this->workerContract
            ->whereHas('worker_operator')
            ->whereIn('franchise_id', $franchise_ids)
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
