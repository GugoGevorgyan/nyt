<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AddressFavoriteResource
 * @package Src\Http\Resources\Driver
 */
class AddressFavoriteResource extends JsonResource
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
            'address_id' => $this->driver_address_id,
            'address' => $this->driver_address,
            'lat' => $this->lat,
            'lut' => $this->lut,
            'target' => $this->target,
        ];
    }
}
