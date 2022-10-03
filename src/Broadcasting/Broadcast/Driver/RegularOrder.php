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
 * Class RegularOrder
 * @package Src\Events\Order
 */
class RegularOrder extends DriverBaseBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  Driver  $driver
     * @param  PassOrderResource  $order
     */
    public function __construct(protected Driver $driver, protected PassOrderResource $order)
    {
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'order' => PassOrderResource::class
    ])]
    public function broadcastWith(): array
    {
        return ['order' => $this->order];
    }
}
