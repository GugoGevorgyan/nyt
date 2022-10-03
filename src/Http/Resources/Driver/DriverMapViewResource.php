<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class DriverMapViewResource
 * @package Src\Http\Resources\Driver
 */
class DriverMapViewResource extends BaseResource
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
            'driver_id' => $this['driver_id'],
            'current_franchise_id' => $this['current_franchise_id'],
            'current_coordinate' => ['lat' => (float)$this['lat'], 'lut' => (float)$this['lut']],
            'azimuth' => $this['azimuth'],
            'phone' => $this['phone'],
            'name' => $this['name'],
            'surname' => $this['surname'],
            'photo' => $this['photo'],
            'car' => [
                'car_id' => $this['car']['car_id'],
//                'car_classes' => $this['car'],
                'mark' => $this['car']['mark'],
                'model' => $this['car']['model'],
                'color' => $this['car']['color'],
                'sts_number' => $this['car']['sts_number'],
                'state_license_plate' => $this['car']['state_license_plate'],
            ]
        ];
    }
}
