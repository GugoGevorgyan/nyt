<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;

use Illuminate\Database\Eloquent\Builder;
use Src\Broadcasting\Broadcast\Worker\DispatcherOrderCreated;
use Src\Core\Enums\ConstQueue;
use Src\Repositories\Franchisee\FranchiseContract;
use Src\Services\Order\OrderServiceContract;

/**
 * Class DispatcherOrderCreateListen
 * @package Src\Listeners\Worker
 */
class DispatcherOrderCreateListen
{
    /**
     * DispatcherOrderCreateListen constructor.
     * @param  OrderServiceContract  $orderService
     * @param  FranchiseContract  $franchiseContract
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
                ->with(['dispatchers' => fn($query) => $query->select(['system_worker_id', 'franchise_id'])])
                ->findAll(['franchise_id']);

            foreach ($franchises as $franchise) {
                foreach ($franchise->dispatchers as $dispatcher) {
                    DispatcherOrderCreated::broadcast($dispatcher->system_worker_id, $franchise->franchise_id, $order);
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
