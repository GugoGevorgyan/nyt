<?php

declare(strict_types=1);

namespace Src\Events\Waybill;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\Worker\WaybillListCreateListener;
use Src\Models\Terminal\Waybill;

/**
 * Class CreateEvent
 * @package Src\Events\Waybill
 * @link WaybillListCreateListener
 */
class WaybillCreate
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  Waybill  $waybill
     */
    public function __construct(public Waybill $waybill)
    {
    }
}
