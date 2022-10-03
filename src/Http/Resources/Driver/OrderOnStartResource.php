<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class OrderOnStartResource
 * @package Src\Http\Resources\Driver
 */
class OrderOnStartResource extends BaseResource
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
            'road_id' => $this->get('order_in_process_road_id'),
            'price' => $this->get('coin'),
            'initial' => $this->get('initial'),

            'hash_pause' => $this->get('pause_hash'),
            'hash_end' => $this->get('end_hash'),
            'address_to' => $this['to'] ?? '',
            'to_cord' => $this['to_cord'] ?? [],

            'route' => $this->get('route', []),
        ];
    }
}
