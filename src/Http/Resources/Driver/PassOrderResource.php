<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class PassOrderResource
 * @package Src\Http\Resources\Driver
 */
class PassOrderResource extends BaseResource
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
            'rent' => $this['rent'] ?? false,
            'rent_hour' => $this['rent_hour'] ?? 0,
            'company_name' => $this['company_name'] ?? '',
            'comment' => $this['comment'] ?? '',
            'shipped_id' => $this['shipped_id'] ?? 0,
            'accept_hash' => $this['accept_hash'] ?? '',
            'rating_rejected' => $this['rating_rejected'] ?? 0,
            'rating_accepted' => $this['rating_accepted'] ?? 0,
            'address_from' => ($this['address_from'] ?? false) ? address_short($this['address_from']) : '',
            'cord_from' => $this['cord_from'] ?? (object)null,
            'distance' => $this['distance'] ?? 0,
            'duration' => $this['duration'] ?? 0.0,
            'delivery_time' => $this['delivery_time'] ?? 0,
            'points' => $this['points'] ?? $this['routes']['points'] ?? $this['routes'] ?? [],
            'client_phone' => $this['client_phone'] ?? ''
        ];
    }
}
