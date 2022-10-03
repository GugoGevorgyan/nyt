<?php

declare(strict_types=1);

namespace Src\Http\Resources\AdminCorporate;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Car\CarClassesResource;
use Src\Http\Resources\Car\CarOptionResource;
use Src\Http\Resources\Models\PaymentTypeResource;

/**
 * Class OpenOrderResource
 * @package Src\Http\Resources\AdminCorporate
 */
class OpenOrderResource extends BaseResource
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
            'car_class' => CarClassesResource::collection($this['car_classes']),
            'car_options' => CarOptionResource::collection($this['car_options']),
            'payment_types' => PaymentTypeResource::collection($this['payment_types']),
            'rent_times' => $this['rent_times'],
            'limit_message' => $this['check_limit']
        ];
    }
}
