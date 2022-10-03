<?php

declare(strict_types=1);

namespace Src\Http\Resources\Client;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Stations\AirportResources;
use Src\Http\Resources\Stations\MetroResources;
use Src\Http\Resources\Stations\RailwayResources;

/**
 * Class StationResource
 * @package Src\Http\Resources\Client
 */
class StationResource extends BaseResource
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
            'airports' => AirportResources::collection($this['airports']),
            'metros' => MetroResources::collection($this['metros']),
            'railways' => RailwayResources::collection($this['railways']),
        ];
    }
}
