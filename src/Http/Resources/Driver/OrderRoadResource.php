<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class OrderRoadResource
 * @package Src\Http\Resources\Driver
 */
class OrderRoadResource extends BaseResource
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
            'road_id' => $this['order_on_way_road_id'] ?? $this['order_in_process_road_id'] ?? 0,
            'distance' => $this['distance'] ?? 0,
            'duration' => $this['duration'] ?? 0,
            'points' => $this['route'] ?? $this['routes']['route'] ?? $this['points'] ?? [],
        ];
    }
}
