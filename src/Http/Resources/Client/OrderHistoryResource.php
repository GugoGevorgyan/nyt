<?php

declare(strict_types=1);

namespace Src\Http\Resources\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class OrderHistoryResource extends BaseResource
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
            'address_from' => $this['address_from'],
            'address_to' => $this['address_to'],
            'order_time' => $this['preorder']
                ? Carbon::parse($this['preorder']['time'])->format('Y-m-d')
                : Carbon::parse($this['created_at'])->format('Y-m-d'),
            'payment_type' => $this['paymentType'] ?? [],
            'from_coordinates' => $this['from_coordinates'] ?? '',
            'to_coordinates' => $this['to_coordinates'] ?? '',
            'in_process_road' => $this['in_process_road'] ?? [],
            'distance' => $this['distance'] ?? '',
            'duration' => $this['duration'] ?? '',
            'status' => $this['status'] ?? [],
            'passenger' => $this['passenger'] ?? [],
            'order_start' => $this['stage']
                ? Carbon::parse($this['stage']['started'])->format('H:i')
                : Carbon::parse($this['created_at'])->format('H:i'),
            'order_end' => $this['stage']
                ? Carbon::parse($this['stage']['ended'])->format('H:i')
                : Carbon::parse($this['created_at'])->format('H:i'),
            'price' => $this['completed'] ? $this['completed']['cost'] : 0,
            'company' => $this['company'] ? [
                'name' => $this['company']['name']
            ] : [],
            'initial_price' => $this['initial_data'] ? $this['initial_data']['price'] : 0,
        ];
    }
}
