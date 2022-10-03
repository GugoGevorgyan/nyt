<?php

declare(strict_types=1);

namespace Src\Http\Resources\Car;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class CarClassesResource
 * @package Src\Http\Resources\Car
 */
class CarClassesResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $options = $this['options'] ?? $this['options'] ?? [];

        return [
            'class_id' => $this['car_class_id'],
            'name' => $this['class_name'],
            'coin' => $this['minimal_price'] ?? $this['coin'] ?? '',
            'currency' => $this['currency'] ?? 'RUB',
            'image' => $this['image'],
            'options' => $options ? CarOptionResource::collection($options) : [],
        ];
    }
}
