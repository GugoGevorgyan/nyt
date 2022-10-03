<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class GetDayOrdersResource
 * @package Src\Http\Resources\Driver
 */
class GetDayOrdersResource extends BaseResource
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
            'days_cost' => $this->get('price', 0.0),
            'days_orders' => $this->get('count', 0),
            'rating' => $this->get('rating', 0),
            'assessment' => $this->get('assessment', 0.0),
        ];
    }
}
