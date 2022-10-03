<?php

declare(strict_types=1);

namespace Src\Http\Resources\ClientMobile;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class GetOrdersDetailResource
 * @package Src\Http\Resources\ClientMobile
 */
class GetOrdersDetailResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'order_id' => $this['order']['order_id'],
            'from' => $this['order']['address_from'],
            'to' => $this['completed_order']['destination_address'] ?? $this['order']['address_to'],
            'trajectory' => $this['completed_order']['trajectory'],
            'price' => $this['completed_order']['cost'],
            'payment_type' => $this['order']['paymentType']['name'],
            'datetime' => [
                'start_time' => Carbon::parse($this['completed_order']['stage']['started'])->format('H:i'),
                'end_time' => Carbon::parse($this['completed_order']['stage']['ended'])->format('H:i'),
                'start_date' => Carbon::parse($this['completed_order']['stage']['started'])->format('d/m/y'),
                'end_date' => Carbon::parse($this['completed_order']['stage']['ended'])->format('d/m/y'),
            ],
            'driver' => [
                'name' => $this['completed_order']['driver_info']['name'] ?? '',
                'surname' => $this['completed_order']['driver_info']['surname'] ?? '',
                'phone' => $this['completed_order']['driver']['phone'] ?? ''
            ],
            'car' => [
                'mark' => $this['completed_order']['car']['mark'] ?? '',
                'model' => $this['completed_order']['car']['model'] ?? '',
                'color' => $this['completed_order']['car']['color'] ?? '',
                'state_license' => $this['completed_order']['car']['state_license_plate'] ?? '',
                'car_class' => $this['order']['carClass']['class_name']
            ],
        ];
    }
}
