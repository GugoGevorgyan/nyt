<?php

declare(strict_types=1);

namespace Src\Http\Resources;

use Illuminate\Http\Request;
use Src\Http\Resources\Worker\OrderShippedDriverResources;

/**
 * Class OrderAttachResource
 * @package Src\Http\Resources
 */
class OrderAttachResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'order_attach_id' => $this['order_attach_id'],
            'order_id' => $this['order_id'],
            'driver_id' => $this['driver_id'],
            'worker_id' => $this['system_worker_id'],
            'shipped_id' => $this['shipped_id'],
            'accepted' => $this['accepted'],
            'created_at' => $this['created_at'],
            'driver_info' => $this['driver_info'],
            'shipped' => $this['shipped'] ? new OrderShippedDriverResources($this['shipped']) : []
        ];
    }
}
