<?php

declare(strict_types=1);

namespace Src\Http\Resources\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class GetPreOrdersResource
 * @package Src\Http\Resources\Client
 */
class GetPreOrdersResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = '_payload';

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return $this->resource ? [
            'order_id' => $this['order_id'],
            'accepted' => $this['accept'],
            'price' => (float)$this['initial']['price'],
            'addresses' => [
                'from' => $this['order']['address_from'],
                'from_cord' => $this['order']['from_coordinates'],
                'to' => $this['order']['address_to'] ?? '',
                'to_cord' => !empty($this['order']['to_coordinates']) ? $this['order']['to_coordinates'] : (object)null,
            ],
            'time' => [
                'create' => $this['create_time'] ? Carbon::parse($this['create_time'])->format('d/m/y H:i') : '',
                'start' => $this['time'] ? Carbon::parse($this['time'])->format('d/m/y H:i') : '',
            ],
        ] : [];
    }
}
