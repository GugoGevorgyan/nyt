<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\Bookkeeping;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Core\Enums\ConstTransactionType;
use Src\Http\Resources\Car\CarOptionResource;

/**
 * Class TransactionDetailsResource
 * @package Src\Http\Resources\Worker\Bookkeeping
 */
class TransactionDetailsResource extends JsonResource
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
            'type' => [
                'name' => $this['type'] ? $this->transactionTypeTrans($this['type']) : [],
                'type_id' => $this['type'],
            ],
            'park' => $this['park'] ? [
                'id' => $this['park']['park_id'],
                'name' => $this['park']['name'],
                'address' => $this['park']['address'],
            ] : [],
            'worker' => $this['worker'] ? [
                'id' => $this['worker']['system_worker_id'],
                'name' => $this['worker']['name'],
                'surname' => $this['worker']['surname'],
                'patronymic' => $this['worker']['patronymic'],
                'photo' => $this['worker']['photo'] ?? '',
            ] : [],


            'cost' => $this['amount'] ?? 0.00,
            'side_cost' => $this['side_cost'] ?? 0.00,
            'franchise_cost' => $this['franchise_cost'] ?? 0.00,

            'side' => $this['side'] ? $this->sideTransactionData($this['side'], $this['type']) : [],
            'reason' => $this['reason'] ? $this->reasonTransactionData($this['reason'], $this['type']) : [],
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

    /**
     * @param $side
     * @param $type
     * @return array
     */
    protected function sideTransactionData($side, $type): array
    {
        $data = [];

        switch ($type) {
            case ConstTransactionType::WAYBILL()->getValue() || ConstTransactionType::CRASH()->getValue():
                $data = [
                    'id' => $side['driver_id'],
                    'name' => $side['driver_info']['name'],
                    'surname' => $side['driver_info']['surname'],
                    'patronymic' => $side['driver_info']['patronymic'],
                    'address' => $side['driver_info']['address'],
                    'full_name' => $side['driver_info']['name'].' '.$side['driver_info']['surname'],
                    'phone' => $side['phone'],
                ];
                break;
        }

        return $data;
    }

    /**
     * @param $reason
     * @param $type
     * @return array
     */
    protected function reasonTransactionData($reason, $type): array
    {
        $data = [];

        switch ($type) {
            case ConstTransactionType::CRASH()->getValue():
                $data = [
                    'id' => $reason['car_crash_id'],
                    'date_time' => Carbon::parse($reason['dateTime'])->format('Y-m-d H:i'),
                    'address' => $reason['address'],
                    'description' => $reason['description'],
                    'inspection_info' => $reason['inspection_info'],
                    'participant_info' => $reason['participant_info'],
                    'driver_fault' => $reason['our_fault'],
                    'sum' => $reason['act_sum'],
                    'act_number' => $reason['act'],
                    'car' => [
                        'car_id' => $reason['car']['car_id'],
                        'license_number' => $reason['car']['vehicle_licence_number'],
                        'mark' => $reason['car']['mark'],
                        'model' => $reason['car']['model'],
                        'color' => $reason['car']['color'],
                        'year' => $reason['car']['year'],
                    ]
                ];
                break;
            case ConstTransactionType::WAYBILL()->getValue():
                $data = [
                    'id' => $reason['waybill_id'],
                    'number' => $reason['number'],
                    'verified' => $reason['verified'],
                    'signed' => $reason['signed'],
                    'start' => Carbon::parse($reason['start_time'])->format('Y-m-d H:i'),
                    'end' => Carbon::parse($reason['end_time'])->format('Y-m-d H:i'),
                    'comment' => $reason['comment'],
                    'cost' => $reason['price'],
                    'worker' => $reason['worker'] ? [
                        'id' => $reason['worker']['system_worker_id'],
                        'name' => $reason['worker']['name'],
                        'surname' => $reason['worker']['surname'],
                        'patronymic' => $reason['worker']['patronymic'],
                        'photo' => $reason['worker']['photo'],
                    ] : []
                ];
                break;
            case ConstTransactionType::ORDER()->getValue():
                $data = [
                    'id' => $reason['order_id'],
                    'address_from' => $reason['address_from'],
                    'address_to' => $reason['address_to'],
                    'from_coordinates' => $reason['from_coordinates'],
                    'to_coordinates' => $reason['address_to'] ?? $reason['completed']['destination_address'],
                    'initial_to_coordinates' => $reason['address_to'],
                    'initial_price' => $reason['initial_data']['price'],
                    'options' => CarOptionResource::collection($reason['car_options']),
                    'client' => [
                        'id' => $reason['client_id'],
                        'name' => $reason['client']['name'],
                        'surname' => $reason['client']['surname'],
                        'patronymic' => $reason['client']['patronymic'],
                        'phone' => $reason['client']['phone'],
                    ]
                ];
                break;
        }

        return $data;
    }
}
