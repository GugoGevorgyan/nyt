<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;

use Src\Broadcasting\Broadcast\Worker\OperatorOrderUpdated;
use Src\Core\Enums\ConstQueue;
use Src\Services\Order\OrderServiceContract;

/**
 * Class OperatorOrderUpdateListener
 * @package Src\Listeners\Worker
 */
class OperatorOrderUpdateListener
{
    /**
     * OperatorOrderUpdateListener constructor.
     */
    public function __construct(protected OrderServiceContract $orderService)
    {
    }

    /**
     * @param $event
     */
    public function handle($event): void
    {
        if ($event->order->operator_id) {
            $order = $this->orderService->getOrderForOperator($event->order->order_id);

            if ($order) {
                $worker = $order->operator;
                OperatorOrderUpdated::broadcast($worker->system_worker_id, $worker->franchise_id, $order);
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
