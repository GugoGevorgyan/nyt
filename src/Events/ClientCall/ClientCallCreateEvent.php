<?php

declare(strict_types=1);

namespace Src\Events\ClientCall;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\Worker\DispatcherClientCallCreateListener;
use Src\Models\Client\ClientCall;

/**
 * Class ClientCallCreateEvent
 * @package Src\Events\ClientCall
 * @link DispatcherClientCallCreateListener
 */
class ClientCallCreateEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * ClientCallCreateEvent constructor.
     * @param  ClientCall  $clientCall
     */
    public function __construct(public ClientCall $clientCall)
    {
    }
}
