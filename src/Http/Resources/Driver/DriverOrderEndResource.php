<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class DriverOrderEndResource
 * @package Src\Http\Resources\Driver
 */
class DriverOrderEndResource extends BaseResource
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
            'price' => $this->gets('price.price', 0.0),
            'currency' => $this->gets('price.currency', 'RUB'),
            'percent_price' => $this->get('driver_price', 0.0),
            'distance' => $this->get('distance', 0),
            'travel_time' => $this->get('duration', 0),
            'pause' => $this->get('pause', 0),
            'slip' => $this->get('slip'),
            'deposit' => $this->get('balance', 0.0),
            'cord' => [
                'address_from' => $this->get('address_from'),
                'address_to' => $this->get('address_to'),
                'from_cord' => $this->get('from_cord'),
                'to_cord' => $this->get('to_cord'),
            ]
        ];
    }
}
