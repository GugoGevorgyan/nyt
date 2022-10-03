<?php

declare(strict_types=1);

namespace Src\Events\SystemWorker;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class WorkerOperatorUpdateEvent
 * @package Src\Events
 */
class WorkerOperatorUpdateEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * WorkerOperatorUpdateEvent constructor.
     * @param $operator
     */
    public function __construct(public $operator)
    {
    }
}
