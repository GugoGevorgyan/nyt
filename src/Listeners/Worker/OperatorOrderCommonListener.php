<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;

use Src\Broadcasting\Broadcast\Worker\OperatorOrderCommon;
use Src\Core\Enums\ConstQueue;

/**
 * Class OperatorOrderCommonListener
 * @package Src\Listeners\Worker
 */
class OperatorOrderCommonListener
{
    /**
     * OperatorOrderCommonListener constructor.
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

        if ($common->order->operator_id) {
            $worker = $common->order->operator;
            OperatorOrderCommon::broadcast($worker->system_worker_id, $worker->franchise_id, $event->common);
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
