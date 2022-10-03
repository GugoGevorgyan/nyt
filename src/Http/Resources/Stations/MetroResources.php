<?php

declare(strict_types=1);

namespace Src\Http\Resources\Stations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MetroResources
 * @package Src\Http\Resources\Stations
 */
class MetroResources extends JsonResource
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
            'metro_id' => $this['metro_id'],
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
