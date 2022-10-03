<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class OrderShippedResource
 * @property mixed passenger_phone
 * @package Src\Http\Resources\Driver
 */
class OrderShippedResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        if (isset($this['reject'])) {
            return [
                'rejected' => $this['reject'],
                'common_rejected' => $this['common_reject'],
            ];
        }

        return [
            'order_id' => $this['order_id'],
            'hash' => $this['generate_on_way_hash'],
            'routes' => OrderRoadResource::collection($this->resource['routes']),
        ];
    }
}
