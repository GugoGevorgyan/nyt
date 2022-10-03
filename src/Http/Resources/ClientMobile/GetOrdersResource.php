<?php

declare(strict_types=1);

namespace Src\Http\Resources\ClientMobile;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class GetOrdersResource
 * @package Src\Http\Resources\ClientMobile
 */
class GetOrdersResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'order_id' => $this['order_id'],
            'ordered_from' => $this['order']['address_from'],
            'ordered_to' => $this['destination_address'] ?? $this['order']['address_to'],
            'cost' => $this['cost'],
            'started' => $this['stage']['started'] ? Carbon::parse($this['stage']['started'])->format('H:i d/m/y') : '',
            'ended' => $this['stage']['ended'] ? Carbon::parse($this['stage']['ended'])->format('H:i d/m/y') : '',
        ];
    }
}
