<?php

declare(strict_types=1);

namespace Src\Http\Resources\Terminal;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class AddCashResource
 * @package Src\Http\Resources\Terminal
 */
class AddCashResource extends BaseResource
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
            'cash' => $this['cash'] ?? 0.0,
            'deposit' => $this['balance'] ?? 0.0,
            'debt' => [
                'debt' => $this['debt'] ?? 0.0,
                'all_debt' => $this['all_debt'] ?? 0.0,
                'repay' => $this['repay'] ?? false,
            ],
            'waybill' => [
                'waybill' => $this['debt_waybill'] ?? 0.0,
                'repay' => $this['repay'] ?? false,
            ]
        ];
    }
}
