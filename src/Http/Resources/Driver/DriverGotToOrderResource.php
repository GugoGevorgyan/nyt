<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use Src\Http\Resources\BaseResource;

/**
 * Class DriverGotToOrderResource
 * @package Src\Http\Resources\Driver
 */
class DriverGotToOrderResource extends BaseResource
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
        'message' => 'mixed|string',
        'routes' => 'array|mixed'
    ])] public function toArray($request): array
    {
        return [
            'order_id' => $this['order_id'],
            'hash' => $this['hash'],
            'message' => $this['message'] ?? '',
            'routes' => $this['routes'] ?? [],
        ];
    }
}
