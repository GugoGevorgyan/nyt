<?php

declare(strict_types=1);

namespace Src\Broadcasting\Broadcast\AdminCorporate;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Core\Enums\ConstChannels;
use Src\Http\Resources\AdminCorporate\OrderHistoryResource;
use Src\Models\Corporate\AdminCorporate;

/**
 * Class CancelOrder
 * @package Src\Broadcasting\Broadcast\AdminCorporate
 */
class CancelOrder implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var OrderHistoryResource
     */
    public OrderHistoryResource $order;
    /**
     * @var AdminCorporate
     */
    protected AdminCorporate $admin;

    /**
     * Create a new event instance.
     *
     * @param  AdminCorporate  $admin
     * @param  OrderHistoryResource  $order
     */
    public function __construct(AdminCorporate $admin, OrderHistoryResource $order)
    {
        $this->admin = $admin;
        $this->order = $order;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new PresenceChannel(ConstChannels::admin_corporate_web()->getValue()."-base.{$this->admin->admin_corporate_id}.{$this->admin->company_id}.{$this->admin->franchise_id}");
    }
}
