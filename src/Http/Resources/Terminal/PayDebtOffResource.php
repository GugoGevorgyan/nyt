<?php

declare(strict_types=1);

namespace Src\Http\Resources\Terminal;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class PayDebtOffResource
 * @package Src\Http\Resources\Terminal
 */
class PayDebtOffResource extends BaseResource
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
            'debt_payed' => $this['debt_payed'] ?? 0.0,
            'debt_left' => $this['debt_left'] ?? 0.0,
            'passed_deposit' => $this['balance'] ?? 0.0,
        ];
    }
}
