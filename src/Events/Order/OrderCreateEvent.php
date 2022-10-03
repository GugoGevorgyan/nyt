<?php

declare(strict_types=1);

namespace Src\Events\Order;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\AdminCorporate\UpdateOrderListen;
use Src\Listeners\Worker\DispatcherOrderCreateListen;
use Src\Listeners\Worker\OperatorOrderCreateListen;
use Src\Models\Order\Order;

/**
 * Class OrderCreateEvent
 * @package Src\Events\Order
 * @link DispatcherOrderCreateListen
 * @link UpdateOrderListen
 * @link OperatorOrderCreateListen
 */
class OrderCreateEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * OrderCreateEvent constructor.
     * @param  Order  $order
     * @link OperatorOrderCreateListen
     * @link DispatcherOrderCreateListen
     * @link UpdateOrderListen
     */
    public function __construct(public Order $order)
    {
    }
}
