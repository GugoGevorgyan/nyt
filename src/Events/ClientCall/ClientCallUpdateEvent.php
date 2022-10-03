<?php

declare(strict_types=1);

namespace Src\Events\ClientCall;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\Worker\DispatcherClientCallUpdateListener;
use Src\Models\Client\ClientCall;

/**
 * Class ClientCallUpdateEvent
 * @package Src\Events\ClientCall
 * @link DispatcherClientCallUpdateListener
 */
class ClientCallUpdateEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var ClientCall
     */
    public ClientCall $clientCall;

    /**
     * ClientCallUpdateEvent constructor.
     * @param  ClientCall  $clientCall
     */
    public function __construct(ClientCall $clientCall)
    {
        $this->clientCall = $clientCall;
    }
}
