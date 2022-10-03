<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\Waybill;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class WaybillReportResource
 * @package Src\Http\Resources\Worker\Waybill
 */
class WaybillReportResource extends BaseResource
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
            'report_id' => $this['car_report_id'],
            'verified' => $this['verified'],
            'comment' => $this['comment'],
            'question' => trans($this['question']['question']),
        ];
    }
}
