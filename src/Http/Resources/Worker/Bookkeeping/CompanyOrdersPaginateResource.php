<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\Bookkeeping;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class CompanyPaginateResources
 * @package Src\Http\Resources\Worker\Bookkeeping
 */
class CompanyOrdersPaginateResource extends BaseResource
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
            'id' => $this['order_id'],
            'address_from' => $this['address_from'],
            'address_to' => $this['address_to'],
            'created_at' => $this['created_at'],

            'accepted' => $this['stage']['accepted'],
            'in_placed' => $this['stage']['in_placed'],
            'started' => $this['stage']['started'],
            'ended' => $this['stage']['ended'],

            'customer_name' => $this['customer']['full_name'] ?? null,

            'tariff' => $this['tariff'] ? [
                'minimal_price' => $this['tariff']['minimal_price'],

                'free_wait_minutes' => $this['tariff']['free_wait_minutes'],
                'paid_wait_minute' => $this['tariff']['paid_wait_minute'],

                'price_min' => $this['tariff']['current_tariff']['price_min'],
                'price_km' => $this['tariff']['current_tariff']['price_km'],

                'behind_price_min' => $this['tariff']['current_tariff']['behind']['price_min'] ?? 0,
                'behind_price_km' => $this['tariff']['current_tariff']['behind']['price_km'] ?? 0,
            ] : [],

            'waiting_time' => round($this['process']['waiting_time'] / 60),
            'pause_time' => round($this['process']['pause_time'] / 60),

            'in_duration' => $this['crossing']['in_duration'] ?? 0,
            'in_duration_price' => $this['crossing']['in_duration_price'] ?? 0,
            'in_distance' => $this['crossing']['in_distance'] ?? 0,
            'in_distance_price' => $this['crossing']['in_distance_price'] ?? 0,
            'in_price' => $this['crossing']['in_price'] ?? 0,

            'out_duration' => $this['crossing']['out_duration'] ?? 0,
            'out_duration_price' => $this['crossing']['out_duration_price'] ?? 0,
            'out_distance' => $this['crossing']['out_distance'] ?? 0,
            'out_distance_price' => $this['crossing']['out_distance_price'] ?? 0,
            'out_price' => $this['crossing']['out_price'] ?? 0,

            'total' => $this['completed']['cost'],
            'VAT' => 20,
            'total_with_VAT' => $this['completed']['cost'] * 1.2,
        ];
    }
}
