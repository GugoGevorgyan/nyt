<?php

declare(strict_types=1);

namespace Src\Http\Resources\Terminal;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Car\CarResource;
use Src\Http\Resources\Driver\DriverInfoResource;
use Src\Http\Resources\Models\WaybillResource;

/**
 * Class PayWaybillResource
 * @package Src\Http\Resources\Terminal
 */
class PayWaybillResource extends BaseResource
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
            'transaction_id' => $this['transaction_id'] ?? null,
            'car' => $this['car'] ? new CarResource($this['car']) : [],
            'driver' => $this['driver_info'] ? new DriverInfoResource($this['driver_info']) : [],

            'waybills' => [
                'number' => $this['waybill_number'],
                'start_date' => $this['start_date'],
                'end_date' => $this['end_date'],
            ],
            'debt' => [
                'payed' => $this['debt']['debt_payed'] ?? 0.0,
                'left' => $this['debt']['debt_left'] ?? 0.0,
                'deposit' => $this['debt']['balance'] ?? 0.0,
            ],
            'park' => [
                'entity' => [
                    'full_title' => $this['park']['entity']['full_title']
                ]
            ]
        ];
    }
}
