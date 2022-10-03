<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\OrderPausesResource;

/**
 * Class OrderListTrajectoryResource
 * @property mixed completed_order_id
 * @property mixed destination_address
 * @package Src\Http\Resources\Driver
 */
class OrderListTrajectoryResource extends BaseResource
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
            'completed_order_id' => $this['completed_order_id'] ?? 0,
            'from' => $this['order']['address_from'],
            'to' => $this['destination_address'] ?? $this['order']['address_to'],
            'cache_type' => trans($this['payment_type']['text']),
            'cost' => $this['cost'],
            'distance' => $this['distance'] ?? 0.0,
            'duration' => $this['duration'] ?? 0.0,
            'datetime' => $this['stage'] ? [
                'start_date' => Carbon::parse($this['stage']['started'])->format('d/m/y') ?: 0.0,
                'end_date' => Carbon::parse($this['stage']['ended'])->format('d/m/y') ?: 0.0,
                'start_time' => Carbon::parse($this['stage']['started'])->format('H:i') ?: 0.0,
                'end_time' => Carbon::parse($this['stage']['ended'])->format('H:i') ?: 0.0,
                'pause_time' => $this['process']['pause_time'],
            ] : [],
            'trajectory' => $this['trajectory'] ?? [],
            'tariff' => $this['tariff'] ? [
                'name' => $this['tariff']['name'],
                'minimal_price' => $this['tariff']['minimal_price'],
                'price_km' => $this['tariff']['current_tariff']['price_km'],
                'price_min' => $this['tariff']['current_tariff']['price_min'],
            ] : [],
            'in_out' => $this['crossing']['in_price'] ?? []
                    ? [
                        'in' => [
                            'in_price' => $this['crossing']['in_price'],
                            'in_distance' => $this['crossing']['in_distance'],
                            'in_duration' => $this['crossing']['in_duration'],
                            'in_trajectory' => $this['crossing']['in_trajectory'],
                        ],
                        'out' => [
                            'out_price' => $this['crossing']['out_price'],
                            'out_distance' => $this['crossing']['out_distance'],
                            'out_duration' => $this['crossing']['out_duration'],
                            'out_trajectory' => $this['crossing']['out_trajectory'],
                        ]
                    ]
                    : [],
            'additional_price' => $this['process'] ? [
                'options' => $this['process']['options_price'],
                'waiting' => $this['process']['waiting_price'],
                'pause' => $this['process']['pause_price'],
            ] : [],
            'pauses' => $this['stage'] && $this['stage']['pauses'] ? OrderPausesResource::collection($this['stage']['pauses']) : [],
        ];
    }
}
