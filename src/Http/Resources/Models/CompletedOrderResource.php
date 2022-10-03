<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Car\CarResource;
use Src\Http\Resources\Driver\DriverInfoResource;
use Src\Http\Resources\Driver\DriverResource;

/**
 * Class CompletedOrderResource
 * @package Src\Http\Resources
 */
class CompletedOrderResource extends BaseResource
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
            'completed_id' => $this['completed_order_id'],
            'car_id' => $this['car_id'],
            'order_id' => $this['order_id'],
            'driver_id' => $this['driver_id'],
            'destination_lat' => $this['destination_lat'],
            'destination_lut' => $this['destination_lut'],
            'destination_address' => $this['destination_address'],
            'distance' => $this['distance'],
            'duration' => $this['duration'],
            'distance_price' => $this['distance_price'],
            'duration_price' => $this['duration_price'],
            'cost' => $this['cost'],
            'car' => $this['car'] ? new CarResource($this['car']) : [],
            'driver_info' => $this['driver_info'] ? new DriverInfoResource($this['driver_info']) : [],
            'driver' => $this['driver'] ? new DriverResource($this['driver']) : [],
        ];
    }
}
