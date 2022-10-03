<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class TariffResource
 * @package Src\Http\Resources
 */
class TariffResource extends BaseResource
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
            'tariff_id' => $this['tariff_id'],
            'name' => $this['name'],
            'minimal_price' => $this['minimal_price'],
            'current_tariff' => $this['current_tariff'] ?: [],
        ];
    }
}
