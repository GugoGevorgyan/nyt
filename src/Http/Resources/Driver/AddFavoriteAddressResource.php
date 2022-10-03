<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AddFavoriteAddressResource
 * @package Src\Http\Resources\Driver
 */
class AddFavoriteAddressResource extends JsonResource
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
            'address' => $this->address,
        ];
    }
}
