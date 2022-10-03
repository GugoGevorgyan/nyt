<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\Waybill;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class WaybillData
 * @package Src\Http\Resources\Worker\Waybill
 */
class WaybillData extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $format = 'H:i d/m/y';

        return [
            'waybill_id' => $this['waybill_id'],
            'number' => $this['number'],
            'verified' => $this['verified'],
            'signed' => $this['signed'],
            'start_time' => Carbon::parse($this['start_time'])->format($format),
            'end_time' => Carbon::parse($this['end_time'])->format($format),
            'created' => Carbon::parse($this['created_at'])->format($format),
            'reported' => $this['car_report']['car_report_id'] ?? false,
            'sum' => (float)$this['amount'] / 2,
            'transaction_sum' => $this['transaction']['amount'] ?? $this['amount'],
            'comment' => $this['comment'],
            'annulled' => $this['deleted_at'] ? Carbon::parse($this['deleted_at'])->format($format) : false,
            'car' => [
                'mark' => $this['car']['mark'],
                'model' => $this['car']['model'],
                'state_license' => $this['car']['state_license_plate'],
            ],
            'driver' => [
                'name' => $this['driver_info']['name'],
                'surname' => $this['driver_info']['surname'],
                'patronymic' => $this['driver_info']['patronymic'],
            ],
        ];
    }
}
