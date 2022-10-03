<?php

declare(strict_types=1);

namespace Src\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class TrafficSafetyCheckEvent
 * @package Src\Events
 */
class TrafficSafetyCheckEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param $cars
     */
    public function __construct(public array $cars)
    {
    }
}
