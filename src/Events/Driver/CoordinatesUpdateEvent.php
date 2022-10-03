<?php

declare(strict_types=1);

namespace Src\Events\Driver;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\Order\Calc\RealCounter;

/**
 * Class CoordinatesUpdateEvent
 * @package Src\Events
 */
class CoordinatesUpdateEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param $driver_id
     * @param $latitude
     * @param $longitude
     * @param $penultCordUpdated
     * @param $penultSpeed
     * @param $speed
     * @link CoordsUpdateListener
     * @link RealCounter
     */
    public function __construct(public $driver_id, public $latitude, public $longitude, public $penultCordUpdated, public $penultSpeed, public $speed)
    {
    }
}
