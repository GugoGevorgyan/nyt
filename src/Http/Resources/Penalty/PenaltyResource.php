<?php

declare(strict_types=1);

namespace Src\Http\Resources\Penalty;

use Illuminate\Http\Resources\Json\JsonResource;

class PenaltyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'debt_id' => $this['debt_id'],
            'cost' => $this['cost'],
            'firm_paid' => $this['firm_paid'],
            'current_debt' => $this['current_debt'],
            'car_full_name' => $this['current_debt']['car']['mark'] . $this['current_debt']['car']['model'],
            'payment_period' => $this['penalty']['pay_bill_date'] .' - '. $this['penalty']['last_bill_date'],
            'penalty' => $this['penalty'],
        ];
    }
}
