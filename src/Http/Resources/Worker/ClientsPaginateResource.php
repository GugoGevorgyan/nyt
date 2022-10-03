<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class ClientsPaginateResource extends BaseResource
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
            'client' => (new ClientResource($this->resource)),
            'orders_count' => $this['completed_orders_count'],
            'orders_sum' => $this['completed_orders_sum_cost'],
            'canceled_orders_count' => $this['canceled_orders_count'],
            'assessed_count' => $this['assessed_count'],
        ];
    }
}
