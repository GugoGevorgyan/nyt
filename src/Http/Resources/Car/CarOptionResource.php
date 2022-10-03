<?php

declare(strict_types=1);

namespace Src\Http\Resources\Car;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class CarOptionResource
 * @package Src\Http\Resources\Car
 */
class CarOptionResource extends BaseResource
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
            'id' => $this['car_option_id'],
            'option' => $this['option'],
            'name' => $this['name'] ? trans($this['name']) : '',
            'value' => $this['value'] ?? '',
            'price' => $this['price'],
        ];
    }
}
