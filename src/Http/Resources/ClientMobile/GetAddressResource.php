<?php

declare(strict_types=1);

namespace Src\Http\Resources\ClientMobile;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class GetAddressResource
 * @package Src\Http\Resources\ClientMobile
 */
class GetAddressResource extends BaseResource
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
            'address_id' => $this['client_address_id'],
            'name' => $this['name'],
            'short_address' => $this['short_address'],
            'address' => $this['address'],
            'favorite' => $this['favorite'] ? true : false,
            'cords' => [
                'lat' => $this['coordinates']['lat'],
                'lut' => $this['coordinates']['lut'],
            ]
        ];
    }
}
