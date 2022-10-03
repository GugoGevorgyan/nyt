<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Car\CarOptionResource;

/**
 * Class OrderResource
 * @package Src\Http\Resources
 */
class OrderResource extends BaseResource
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
            'order_id' => $this['order_id'],
            'customer_id' => $this['customer_id'],
            'customer_type' => $this['customer_id'],
            'client_id' => $this['client_id'],
            'status_id' => $this['status_id'],
            'status' => $this['status'] ?: [],
            'address_from' => $this['address_from'],
            'address_to' => $this['address_to'],
            'from_cord' => $this['from_coordinates'],
            'to_cord' => $this['to_coordinates'],
            'initial_price' => $this['initial_data'] ? $this['initial_data']['price'] ?? '' : '',
            'car_options' => $this['car_options'] ? CarOptionResource::collection($this['car_options']) : [],
        ];
    }
}
