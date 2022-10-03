<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class CorporateOrderResource extends BaseResource
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
            'order_corporate_id' => $this['corporate_id'] ?? '',
            'order_id' => $this['order_id'] ?? '',
            'company_id' => $this['company_id'] ?? '',
            'driver_id' => $this['driver_id'] ?? '',
            'slip_number' => $this['slip_number'] ?? '',
        ];
    }
}
