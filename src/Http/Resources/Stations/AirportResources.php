<?php

declare(strict_types=1);

namespace Src\Http\Resources\Stations;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class AirportResources
 * @package Src\Http\Resources
 */
class AirportResources extends BaseResource
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
            'airport_id' => $this['airport_id'],
            'name' => $this['name'],
            'terminal' => $this['terminal'],
            'cord' => [
                'lat' => (float)$this['lat'],
                'lut' => (float)$this['lut'],
            ],
            'address' => $this['address'],
            'city' => [
                'city_id' => $this['city']['city_id'],
                'name' => $this['city']['name'],
            ],
        ];
    }
}
