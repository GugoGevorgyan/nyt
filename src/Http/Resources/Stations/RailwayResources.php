<?php

declare(strict_types=1);

namespace Src\Http\Resources\Stations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class RailwayResources
 * @package Src\Http\Resources\Stations
 */
class RailwayResources extends JsonResource
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
            'railway_id' => $this['railway_station_id'],
            'name' => $this['name'],
            'input' => $this['input'],
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
