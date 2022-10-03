<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;

use Src\Broadcasting\Broadcast\Worker\DispatcherOrderCommon;
use Src\Core\Enums\ConstQueue;

/**
 * Class DispatcherOrderCommonListener
 * @package Src\Listeners\Worker
 */
class DispatcherOrderCommonListener
{
    /**
     * DispatcherOrderCommonListener constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $event
     */
    public function handle($event): void
    {
        $common = $event->common;

        foreach ($common->order->franchise as $franchisee) {
            foreach ($franchisee->dispatchers as $dispatcher) {
                DispatcherOrderCommon::broadcast($dispatcher->system_worker_id, $franchisee->franchise_id, $event->common);
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
