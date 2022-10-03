<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

class PassCommonOrderResource extends BaseResource
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
            'order_id' => $this['order_id'] ?? 0,
            'shipped_id' => $this['shipped_id'] ?? 0,
            'accept_hash' => $this['accept_hash'] ?? '',
            'cash' => $this['cash'] ?? false,
            'company_name' => $this['company_name'] ?? '',
            'address_from' => ($this['address_from'] ?? false) ? address_short($this['address_from']) : '',
            'cord_from' => $this['cord_from'] ?? (object)null,
            'delivery_time' => $this['delivery_time'] ? Carbon::parse($this['delivery_time'])->format('d/m/y H:m') : '',
            'distance' => $this['distance'] ?? 0.0,
            'duration' => $this['duration'] ?? 0,
        ];
    }
}
