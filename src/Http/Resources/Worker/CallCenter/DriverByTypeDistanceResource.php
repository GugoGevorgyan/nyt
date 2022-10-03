<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\CallCenter;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class DriverByTypeDistanceResource extends BaseResource
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
            'driver' => [
                'name' => $this['driver_info']['name'].' '.$this['driver_info']['surname'].' '.$this['driver_info']['patronymic'],
                'phone' => $this['phone'],
                'driver_id' => $this['driver_id'],
                'status' => [
                    'status' => $this['status']['status'],
                    'text' => $this['status']['text'],
                    'color' => $this['status']['color'],
                ]
            ],
            'car' => $this['car'] ? [
                'mark' => $this['car']['mark'],
                'model' => $this['car']['model'],
                'year' => $this['car']['year'],
                'color' => $this['car']['color'],
                'license_plate' => $this['car']['state_license_plate'],
            ] : [],
            'accepted' => (bool)$this['order_shipment'],
            'radius' => $this['distance'] ? round_d($this['distance']) : 0,
        ];
    }
}
