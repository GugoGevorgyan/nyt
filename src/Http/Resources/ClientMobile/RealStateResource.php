<?php

declare(strict_types=1);

namespace Src\Http\Resources\ClientMobile;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\GetFeedbackTypesResource;

/**
 * Class RealStateResource
 * @package Src\Http\Resources\ClientMobile
 */
class RealStateResource extends BaseResource
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
            'status' => $this['status'],
            'message' => $this['message'] ?? '',
            'mask' => session('app_system.mask'),

            'payload' => [
                'order' => [
                    'order_id' => $this['order']['order_id'] ?? 0,
                    'address_from' => $this['order']['address_from'] ?? '',
                    'address_to' => $this['order']['address_to'] ?? '',
                    'from_cord' => $this['order']['from_coordinates'] ?? 0.0,
                    'to_cord' => $this['order']['to_coordinates'] ?? 0.0,
                    'price' => $this['order']['price'] ?? 0.0,
                    'sitting_fee' => $this['order']['sitting_fee'] ?? 0,
                    'hash' => $this['order']['hash'] ?? ''
                ],
                'driver' => isset($this['driver']) ? [
                    'name' => $this['driver']['name'].' '.$this['driver']['surname'] ?? '',
                    'photo' => $this['driver']['photo'] ?? '',
                ] : [],
                'car' => [
                    'mark' => $this['car']['name'] ?? '',
                    'model' => $this['car']['model'] ?? '',
                    'state_license_plate' => $this['car']['state_license_place'] ?? '',
                    'color' => $this['car']['color'] ?? '',
                    'on_way_duration' => $this['car']['on_way_duration'] ?? 0
                ],
                'assessment' => isset($this['feedbacks']) ? GetFeedbackTypesResource::collection($this['feedbacks']) : []
            ]
        ];
    }
}
