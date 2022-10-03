<?php

declare(strict_types=1);

namespace Src\Http\Resources\ClientMobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use JetBrains\PhpStorm\ArrayShape;
use Src\Http\Resources\App\CompaniesResource;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Models\PaymentTypeResource;

/**
 * Class IniOpenResource
 * @package Src\Http\Resources\ClientMobile
 */
class IniOpenResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    #[ArrayShape([
        'car_classes' => AnonymousResourceCollection::class,
        'payment_types' => AnonymousResourceCollection::class,
        'companies' => 'array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection',
        'rent_times' => 'mixed'
    ])] public function toArray($request): array
    {
        return [
            'car_classes' => CarClassesResource::collection($this['car_classes']),
            'payment_types' => PaymentTypeResource::collection($this['payment_types']),
            'companies' => $this['companies'] ? CompaniesResource::collection($this['companies']) : [],
            'rent_times' => $this['rent_times']
        ];
    }
}
