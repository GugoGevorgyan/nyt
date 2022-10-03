<?php

declare(strict_types=1);

namespace Src\Http\Resources\App;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Car\CarOptionResource;

/**
 * Class InitCoinResource
 * @package Src\Http\Resources\App
 */
class InitCoinResource extends BaseResource
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
            'car_class_id' => $this['class_id'],
            'class_name' => $this['name'],
            'coin' => $this['coin'],
            'currency' => $this['currency'],
            'distance' => $this['distance'] ?? 0,
            'image' => $this['image'],
            'initial' => $this['initial'] ?? false,
            'name' => $this['name'],
            'sitting_coin' => $this['sitting_coin'] ?? 0,
            'sitting_fee' => $this['sitting_fee'] ?? 0,
            'time' => $this['time'] ?? 0,
            'selected' => $this['selected_class'],
            'rent_times' => $this['rent_times'],
            'car_options' => $this['options'] ? CarOptionResource::collection($this['options']) : [],
        ];
    }
}
