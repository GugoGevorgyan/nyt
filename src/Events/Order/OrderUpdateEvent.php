<?php

declare(strict_types=1);

namespace Src\Events\Order;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\Worker\DispatcherOrderUpdateListener;
use Src\Listeners\Worker\OperatorOrderUpdateListener;
use Src\Models\Order\Order;


/**
 * Class OrderUpdateEvent
 * @package Src\Events
 */
class OrderUpdateEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * OrderUpdateEvent constructor.
     * @param  Order  $order
     * @link OperatorOrderUpdateListener
     * @link DispatcherOrderUpdateListener
     */
    public function __construct(public Order $order)
    {
    }
}
