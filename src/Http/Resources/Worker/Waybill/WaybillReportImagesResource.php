<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\Waybill;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class WaybillReportImagesResource
 * @package Src\Http\Resources\Worker\Waybill
 */
class WaybillReportImagesResource extends BaseResource
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
            'id' => $this['car_report_image_id'],
            'path' => $this['path'],
            'name' => $this['name'],
            'ext' => $this['ext'],
        ];
    }
}
