<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use Src\Http\Resources\BaseResource;

/**
 * Class OrderInPlaceResource
 * @package Src\Http\Resources\Driver
 */
class OrderInPlaceResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    #[ArrayShape([
        'order_id' => 'mixed',
        'hash' => 'mixed',
        'payment_type' => 'mixed|string',
        'address_from' => 'mixed|string',
        'from_cord' => 'mixed|string',
        'address_to' => 'mixed|string',
        'to_cord' => 'array|mixed',
        'routes' => 'array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection'
    ])]
    public function toArray($request): array
    {
        return [
            'order_id' => $this['order_id'],
            'hash' => $this['hash'],
            'payment_type' => $this['payment_type'] ?? '',
            'address_from' => $this['from'] ?? '',
            'from_cord' => $this['from_cord'] ?? '',
            'address_to' => $this['to'] ?? '',
            'to_cord' => $this['to_cord'] ?? [],
            'routes' => !empty($this['routes']) ? OrderRoadResource::collection($this['routes']) : [],
        ];
    }
}
