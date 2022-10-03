<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\CallCenter;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class OrderProcessResource extends BaseResource
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
            'price' => $this['price'] ?? 0,
            'total_price' => $this['total_price'] ?? 0,
            'calculate_price' => $this['calculate_price'] ?? 0,
            'increment_price' => $this['increment_price'] ?? 0,
            'options_price' => $this['options_price'] ?? 0,
            'pause_price' => $this['pause_price'] ?? 0,
            'sitting_price' => $this['sitting_price'] ?? 0,
            'cancel_price' => $this['cancel_price'] ?? 0,
            'waiting_price' => $this['waiting_price'] ?? 0,
            'distance_traveled' => $this['distance_traveled'] ?? 0,
            'travel_time' => $this['travel_time'] ?? 0,
            'pause_time' => $this['pause_time'] ?? 0,
        ];
    }
}
