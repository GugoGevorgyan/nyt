<?php

declare(strict_types=1);

namespace Src\Events\Order;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\AdminCorporate\UpdateOrderListen;
use Src\Models\Order\Order;

/**
 * Class OrderStatusUpdate
 * @package Src\Events\Order
 */
class OrderStatusUpdate
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var Order
     */
    public Order $order;

    /**
     * Create a new event instance.
     *
     * @return void
     * @link UpdateOrderListen
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
