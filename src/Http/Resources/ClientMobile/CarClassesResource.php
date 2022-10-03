<?php

declare(strict_types=1);

namespace Src\Http\Resources\ClientMobile;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Car\CarOptionResource;
use Storage;

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
     * @throws FileNotFoundException
     */
    public function toArray($request): array
    {
        $options = $this['options'] ?? $this['options'] ?? [];

        return [
            'class_id' => $this['car_class_id'],
            'rent_times' => [],
            'name' => $this['class_name'],
            'min_price' => $this['minimal_price'] ?? $this['coin'],
            'currency' => $this['currency'] ?? 'RUB',
            'car_options' => $options ? CarOptionResource::collection($options) : [],
            'image' => base64_encode(Storage::get(str_replace('storage', 'public', $this['image']))),
        ];
    }
}
