<?php

declare(strict_types=1);

namespace Src\Events\Order;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class OrderCommonCreateEvent
 * @package Src\Events\Order
 */
class OrderCommonCreateEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * OrderCommonCreateEvent constructor.
     * @param $common
     */
    public function __construct(public $common)
    {
    }
}
