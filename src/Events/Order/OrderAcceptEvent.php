<?php

declare(strict_types=1);

namespace Src\Events\Order;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\Worker\DispatcherOrderUpdateListener;
use Src\Listeners\Worker\OperatorOrderUpdateListener;

/**
 * Class OrderAcceptEvent
 * @package Src\Events\Order
 */
class OrderAcceptEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * OrderUpdateEvent constructor.
     * @param $order
     * @link OperatorOrderUpdateListener
     * @link DispatcherOrderUpdateListener
     */
    public function __construct(public $order)
    {
    }
}
