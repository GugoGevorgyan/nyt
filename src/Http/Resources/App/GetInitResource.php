<?php

declare(strict_types=1);

namespace Src\Http\Resources\App;

use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Car\CarClassesResource;
use Src\Http\Resources\Client\PayCardResource;
use Src\Http\Resources\Models\PaymentTypeResource;

/**
 * Class GetInitResource
 * @package Src\Http\Resources\App
 */
class GetInitResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    #[ArrayShape(['_payload' => 'array'])] public function toArray($request): array
    {
        return [
            '_payload' => [
                'car_classes' => CarClassesResource::collection($this['car_classes']),
                'companies' => $this['companies'] ? CompaniesResource::collection($this['companies']) : [],
                'payment_types' => PaymentTypeResource::collection($this['payment_types']),
                'pay_cards' => PayCardResource::collection($this['pay_cards']),

//                'phone_mask' => $this['phone_mask'] ?? '',
                'location' => $this['location'] ?? [],
                'rent_times' => $this['rent_times']
            ]
        ];
    }
}
