<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;

use Illuminate\Database\Eloquent\Builder;
use Src\Broadcasting\Broadcast\Worker\DispatcherOrderUpdated;
use Src\Core\Enums\ConstQueue;
use Src\Repositories\Franchisee\FranchiseContract;
use Src\Services\Order\OrderServiceContract;

/**
 * Class DispatcherOrderUpdateListener
 * @package Src\Listeners\Worker
 */
class DispatcherOrderUpdateListener
{
    /**
     * DispatcherOrderUpdateListener constructor.
     */
    public function __construct(protected OrderServiceContract $orderService, protected FranchiseContract $franchiseContract)
    {
    }

    /**
     * @param $event
     */
    public function handle($event): void
    {
        $order = $this->orderService->getOrderForDispatcher($event->order->order_id);

        if ($order) {
            $franchises = $this->franchiseContract
                ->whereHas('orders', fn(Builder $query) => $query->where('order_id', '=', $event->order->order_id))
                ->with('dispatchers')
                ->findAll();

            foreach ($franchises as $franchise) {
                if ($franchise->dispatchers->count()) {
                    foreach ($franchise->dispatchers as $dispatcher) {
                        DispatcherOrderUpdated::broadcast($dispatcher->system_worker_id, $franchise->franchise_id, $order);
                    }
                }
            }
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
