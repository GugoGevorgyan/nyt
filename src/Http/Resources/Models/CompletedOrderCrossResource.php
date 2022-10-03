<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class CompletedOrderCrossResource extends BaseResource
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
            'in_price' => $this['in_price'] ?? 0,
            'out_price' => $this['out_price'] ?? 0,
            'in_distance' => $this['in_distance'] ?? 0,
            'out_distance' => $this['out_distance'] ?? 0,
            'in_distance_price' => $this['in_distance_price'] ?? 0,
            'out_distance_price' => $this['out_distance_price'] ?? 0,
            'in_duration_price' => $this['in_duration_price'] ?? 0,
            'out_duration_price' => $this['out_duration_price'] ?? 0,
            'in_duration' => $this['in_duration'] ?? 0,
            'out_duration' => $this['out_duration'] ?? 0,
            'trajectory' => $this['trajectory'] ?? [],
            'in_trajectory' => $this['in_trajectory'] ?? [],
            'out_trajectory' => $this['out_trajectory'] ?? [],
        ];
    }
}
