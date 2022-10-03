<?php

declare(strict_types=1);

namespace Src\Http\Resources\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

class ClientPreOrdersResource extends BaseResource
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
            '_id' => $this['order']['order_id'],
            'details' => [
                'address_from' => $this['order']['address_from'],
                'address_to' => $this['order']['address_to'],
                'from_coordinates' => $this['order']['from_coordinates'],
                'to_coordinates' => $this['order']['to_coordinates'],
                'created' => Carbon::parse($this['create_time'])->format('y/m/d H:i'),
                'started' => Carbon::parse($this['time'])->format('y/m/d H:i'),
            ],
            'cost' => $this['initial'] ? [
                'price' => $this['initial']['price'],
                'currency' => $this['initial']['currency'],
                'distance' => $this['initial']['distance'],
                'duration' => $this['initial']['duration'],
            ] : [],
            'driver' => $this['shipped_driver'] ? [
                'name' => $this['shipped_driver']['driver_info']['name'],
                'surname' => $this['shipped_driver']['driver_info']['surname'],
                'patronymic' => $this['shipped_driver']['driver_info']['patronymic'],
                'photo' => $this['shipped_driver']['driver_info']['photo'],
            ] : null,
        ];
    }
}
