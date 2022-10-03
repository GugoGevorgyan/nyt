<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Car\CarResource;
use Src\Http\Resources\Driver\DriverInfoResource;
use Src\Http\Resources\Driver\DriverResource;
use Src\Http\Resources\Worker\CallCenter\OrderProcessResource;

/**
 * Class OrderShippedDriverResources
 * @package Src\Http\Resources\Worker
 */
class OrderShippedDriverResources extends BaseResource
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
            'order_shipped_driver_id' => $this['order_shipped_driver_id'] ?? '',
            'driver_id' => $this['driver_id'] ?? '',
            'order_id' => $this['order_id'] ?? '',
            'current' => $this['current'] ?? '',
            'common' => $this['common'] ?? '',
            'created_at' => $this['created_at'],
            'status' => $this['status'] ?: $this['status_id'],
            'driver' => $this['driver'] ? new DriverResource($this['driver']) : [],
            'driver_info' => $this['driver_info'] ? new DriverInfoResource($this['driver_info']) : [],
            'car' => $this['car'] ? new CarResource($this['car']) : [],
            'process' => $this['process'] ? new OrderProcessResource($this['process']) : []
        ];
    }
}
