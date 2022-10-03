<?php

declare(strict_types=1);

namespace Src\Events\Driver;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\Worker\DispatcherDriverUpdateListener;
use Src\Listeners\Worker\OperatorDriverUpdateListener;
use Src\Models\Driver\Driver;

/**
 * Class DriverUpdateEvent
 * @package Src\Events\Driver
 */
class DriverUpdateEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * DriverUpdateEvent constructor.
     *
     * @param  Driver  $driver
     * @link DispatcherDriverUpdateListener
     * @link OperatorDriverUpdateListener
     */
    public function __construct(public Driver $driver)
    {
    }
}
