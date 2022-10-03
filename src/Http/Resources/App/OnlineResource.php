<?php

declare(strict_types=1);

namespace Src\Http\Resources\App;

use Carbon\Carbon;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use Src\Http\Resources\BaseResource;

class OnlineResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    #[ArrayShape([
        'message' => 'string',
        '_payload' => 'array'
    ])] public function toArray($request): array
    {
        return [
            'message' => 'OK',
            '_payload' => [
                'status' => $this['status'],
                'preorder' => $this['preorder'],
                'state' => [
                    'driver' => isset($this['state']['driver']) ? $this['state']['driver'] : [],
                    'status' => $this['state']['status'],
                    'message' =>  isset($this['state']['message']) ? $this['state']['message'] : '',
                    'tariff_features' =>  isset($this['state']['tariff_features']) ? $this['state']['tariff_features'] : [],
                    'minute' => isset($this['state']['minute']) ? $this['state']['minute'] : '',
                ],
                'order' => [
                    'created_at' => Carbon::parse($this['order']['created_at'])->format('Y-m-d H:i:s'),
                    'address_from' => $this['order']['address_from'],
                    'order_id' => $this['order']['order_id'],
                    'address_to' => $this['order']['address_to'],
                    'car_class_id' => $this['order']['car_class_id'],
                    'car_option' => $this['order']['car_option'],
                    'client_id' => $this['order']['client_id'],
                    'comments' => $this['order']['comments'],
                    'customer_id' => $this['order']['customer_id'],
                    'dist_type' => $this['order']['dist_type'],
                    'franchisee' => $this['order']['franchisee'],
                    'from_coordinates' => $this['order']['from_coordinates'],
                    'order_type_id' => $this['order']['order_type_id'],
                    'passenger_id' => $this['order']['passenger_id'],
                    'payment_type_id' => $this['order']['payment_type_id'],
                    'show_cord' => $this['order']['show_cord'],
                    'status_id' => $this['order']['status_id'],
                    'to_coordinates' => $this['order']['to_coordinates'],
                    'repeated_at' => $this['order']['repeated_at']
                ],
                'action' => [
                         'coin' => $this['action']['price'],
                         'currency' => $this['action']['currency'],
                         'distance' => $this['action']['distance'],
                         'initial'=> $this['action']['initial'],
                         'option_price'=> $this['action']['option_price'],
                         'sitting_fee'=> $this['action']['sitting_fee'],
                         'sitting_price'=> $this['action']['sitting_price'],
                ]
            ]
        ];
    }
}
