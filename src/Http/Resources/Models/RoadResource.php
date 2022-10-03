<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class RoadResource
 * @package Src\Http\Resources
 */
class RoadResource extends BaseResource
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
            'road_id' => $this['order_in_process_road_id'] ?? $this['order_on_way_road_id'] ?? '',
            'shipped_id' => $this['shipment_driver_id'] ?? '',
            'route' => $this['route'] ?? [],
            'real_road' => $this['real_road'] ?? [],
            'distance' => $this['distance'] ?? '',
            'duration' => $this['duration'] ?? '',
            'selected' => $this['selected'] ?? '',
        ];
    }
}
