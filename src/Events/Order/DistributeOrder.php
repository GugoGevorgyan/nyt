<?php

declare(strict_types=1);

namespace Src\Events\Order;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class DistributeOrder
 * @package Src\Events\Order
 */
class DistributeOrder
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     * @param $order
     * @param $orderData
     * @param $timer
     * @link \Src\Listeners\Order\Distributor\OrderDistributorStarter
     */
    public function __construct(public $order, public $orderData, public $timer)
    {
    }
}
