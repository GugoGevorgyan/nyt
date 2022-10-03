<?php

declare(strict_types=1);

namespace Src\Events\Order;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\Worker\DispatcherOrderActiveShippedListener;
use Src\Listeners\Worker\OperatorOrderActiveShippedListener;

/**
 * Class OrderShippedDriverEvent
 * @package Src\Events\Order
 */
class OrderShippedDriverEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * OrderShippedDriverEvent constructor.
     *
     * @param  array  $shipped
     * @link DispatcherDriverActiveShippedListener
     * @link OperatorDriverActiveShippedListener
     * @link DispatcherOrderActiveShippedListener
     * @link OperatorOrderActiveShippedListener
     */
    public function __construct(public array $shipped)
    {
    }
}
