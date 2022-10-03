<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\Driver;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\ArrayShape;
use Src\Models\Driver\Driver;

/**
 * Send Preorder started broadcast
 * @method static broadcast(object $driver, array $payload, string $message = '', string $status = self::ACCEPT)
 */
class AcceptPreOrder extends DriverBaseBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public const ACCEPT = 'accept';
    public const ANSWER = 'answer';
    public const NOTY = 'unpinned';

    /**
     * Create a new event instance.
     *
     * @param  Driver  $driver
     * @param  array  $payload
     * @param  string  $message
     * @param  string  $status
     */
    public function __construct(protected Driver $driver, protected array $payload, protected string $message = '', protected string $status = self::ACCEPT)
    {
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'payload' => 'array',
        'message' => 'string',
        'status' => 'int'
    ])]
    public function broadcastWith(): array
    {
        return ['payload' => $this->payload, 'message' => $this->message, 'status' => $this->status];
    }
}
