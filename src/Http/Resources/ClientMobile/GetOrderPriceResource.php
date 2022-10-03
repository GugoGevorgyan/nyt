<?php

declare(strict_types=1);

namespace Src\Http\Resources\ClientMobile;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Car\CarOptionResource;
use Storage;

/**
 * Class GetOrderPriceResource
 * @package Src\Http\Resources\ClientMobile
 */
class GetOrderPriceResource extends BaseResource
{
    /**
     * @var null
     */
    public static $wrap = null;
    /**
     * @var bool
     */
    public bool $preserveKeys = false;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     * @throws FileNotFoundException
     */
    public function toArray($request): array
    {
        return [
            'distance' => $this['distance'] ?? 0,
            'time' => $this['time'] ?? '',
            'sitting_coin' => $this['sitting_coin'] ?? 0,
            'initial' => $this['initial'] ?? 0,
            'sitting_fee' => $this['sitting_fee'] ?? 0,
            'class_id' => $this['class_id'],
            'coin' => $this['coin'],
            'currency' => $this['currency'],
            'name' => $this['name'],
            'selected' => $this['selected_class'],
            'car_options' => $this['options'] ? CarOptionResource::collection($this['options']) : [],
            'rent_times' => $this['rent_times'],
            'image' => base64_encode(Storage::get(str_replace('storage', 'public', $this['image']))),
        ];
    }
}
