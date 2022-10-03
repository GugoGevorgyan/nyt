<?php

declare(strict_types=1);

namespace Src\Events\Order;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\Worker\OperatorOrderAttachListener;

/**
 * Class OrderAttachEvent
 * @package Src\Events\Order
 */
class OrderAttachEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * OrderAttachEvent constructor.
     * @param $order
     * @see OperatorOrderAttachListener
     */
    public function __construct(public $order)
    {
    }
}
