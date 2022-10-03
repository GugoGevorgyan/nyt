<?php

declare(strict_types=1);

namespace Src\Http\Resources\Terminal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Core\Enums\ConstTransactionType;

/**
 * Class GetDebtResource
 * @package Src\Http\Resources\Terminal
 */
class GetDebtResource extends JsonResource
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
            'debt' => $this['debt'] ?? 0.0,
            'max_count_redemption' => $this['amount'] ?? 0,
            'minimal_repayment_amount' => $this['repayment_amount'] ?? 0.0,
            'minimal_repayment_waybill' => $this['repayment_waybill'] ?? 0.0,
            'cash_types' => ConstTransactionType::getAll(),
            'balance' => $this['balance'] ?? 0.0,
            'transaction_cash' => $this['transaction_cash'] ?? 0.0,
            'waybills_allowed_limit' => $this['waybills_allowed_limit']
        ];
    }
}
