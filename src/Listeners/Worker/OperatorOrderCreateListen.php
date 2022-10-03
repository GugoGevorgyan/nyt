<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;

use Src\Broadcasting\Broadcast\Worker\OperatorOrderCreated;
use Src\Core\Enums\ConstQueue;
use Src\Events\Order\OrderCreateEvent;
use Src\Services\Order\OrderServiceContract;

/**
 * Class OperatorOrderCreateListen
 * @package Src\Listeners\Worker
 */
class OperatorOrderCreateListen
{
    /**
     * OperatorOrderCreateListen constructor.
     * @param  OrderServiceContract  $orderService
     */
    public function __construct(protected OrderServiceContract $orderService)
    {
    }

    /**
     * @param  OrderCreateEvent  $event
     */
    public function handle(OrderCreateEvent $event): void
    {
        if ($event->order && $event->order->operator_id) {
            $order = $this->orderService->getOrderForOperator($event->order->order_id);

            if ($order) {
                $worker = $order->operator;
                OperatorOrderCreated::broadcast($worker->system_worker_id, $worker->franchise_id, $order);
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
