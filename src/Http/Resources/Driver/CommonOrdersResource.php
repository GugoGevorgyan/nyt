<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class CommonOrdersResource extends BaseResource
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
            'cash' => $this['cash'] ?? false,
            'company_name' => $this['company_name'] ?? '',
            'rating_rejected' => $this['rating_rejected'] ?? 0,
            'rating_accepted' => $this['rating_accepted'] ?? 0,
            'address_from' => $this['address_from'] ? address_short($this['address_from']) : '',
            'cord_from' => $this['cord_from'] ?? (object)null,
            'delivery_time' => $this['delivery_time'] ? Carbon::parse($this['delivery_time'])->format('d/m/y H:i') : 0,
        ];
    }
}
