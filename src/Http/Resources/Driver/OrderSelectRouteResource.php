<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class OrderSelectRouteResource
 * @package Src\Http\Resources\Driver
 */
class OrderSelectRouteResource extends BaseResource
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
            'order_id' => $this['order']['order_id'],
            'address_to' => $this['order']['address_to'],
            'to_cord' => $this['order']['to_coordinates'],
            'road_id' => $this['road']['order_in_process_road_id'],
            'distance' => $this['road']['distance'],
            'duration' => $this['road']['duration'],
            'route' => $this['road']['route'],
        ];
    }
}
