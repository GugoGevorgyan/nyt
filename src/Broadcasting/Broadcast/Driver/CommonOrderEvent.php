<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Driver;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\ArrayShape;
use Src\Http\Resources\Driver\PassOrderResource;
use Src\Models\Driver\Driver;

/**
 * Class CommonOrderEvent
 * @package Src\Broadcasting\Broadcast\Driver
 * @method static broadcast(Driver $driver, PassOrderResource $output, string $status = 'create'): self
 */
class CommonOrderEvent extends DriverBaseBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  Driver  $driver
     * @param  PassOrderResource  $order
     * @param  string  $status
     */
    public function __construct(protected Driver $driver, protected PassOrderResource $order, protected string $status = 'create')
    {
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'order' => '\Src\Http\Resources\Driver\PassOrderResource',
        'status' => 'string'
    ])]
    public function broadcastWith(): array
    {
        return ['order' => $this->order, 'status' => $this->status];
    }
}
