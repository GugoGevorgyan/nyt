<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\Bookkeeping;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Src\Core\Enums\ConstTransactionType;
use Src\Http\Resources\BaseResource;

/**
 * Class AccountingTenantData
 * @package Src\Http\Resources\Worker\Accounting
 */
class BookkeepingData extends BaseResource
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
            'park' => $this['park'] ? [
                'id' => $this['park']['park_id'],
                'name' => $this['park']['name'],
            ] : [],
            'worker' => $this['worker'] ? [
                'id' => $this['worker']['system_worker_id'],
                'name' => $this['worker']['name'],
                'surname' => $this['worker']['surname'],
                'patronymic' => $this['worker']['patronymic'],
            ] : [],

            'id' => $this['franchise_transaction_id'],
            'number' => $this['number'],
            'remainder' => $this['remainder'] ?? 0,
            'type' => [
                'name' => $this->transactionTypeTrans($this['type']),
                'type' => $this['type'],
            ],
            'franchise_cost' => $this['franchise_cost'],
            'side_cost' => $this['side_cost'],
            'amount' => $this['amount'],
            'out' => $this['out'] ? trans('words.out') : trans('words.in'),
            'payed' => $this['payed'],
            'comment' => $this['comment'],
            'created' => Carbon::parse($this['created_at'])->format('Y-m-d H:i'),
        ];
    }

    /**
     * @param $type
     * @return array|Application|Translator|string|null
     */
    protected function transactionTypeTrans($type)
    {
        $word = '';

        switch ($type) {
            case ConstTransactionType::ORDER()->getValue():
                $word = trans('words.order');
                break;
            case ConstTransactionType::WAYBILL()->getValue():
                $word = trans('words.waybill');
                break;
            case ConstTransactionType::DEBT()->getValue():
                $word = trans('words.debt');
                break;
            case ConstTransactionType::CRASH()->getValue():
                $word = trans('words.crash');
                break;
            case ConstTransactionType::BALANCE()->getValue():
                $word = trans('words.balance');
                break;
        }

        return $word;
    }
}
