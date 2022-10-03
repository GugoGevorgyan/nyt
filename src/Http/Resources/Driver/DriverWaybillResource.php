<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Worker\Waybill\WaybillData;

/**
 * Class DriverWaybillResource
 * @package Src\Http\Resources
 */
class DriverWaybillResource extends BaseResource
{
    public static $wrap = 'driver';

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'driver_id' => $this->driver_id,
            'driver_nickname' => $this->nickname,
            'driver_phone' => $this->phone,
            'driver_email' => $this->driver_info->email,
            'name' => $this->driver_info->name,
            'surname' => $this->driver_info->surname,
            'patronymic' => $this->driver_info->patronymic,
            'full_name' => $this->driver_info->surname . ' ' . $this->driver_info->name . ' ' . $this->driver_info->patronymic,
            'type' => new DriverTypeResource($this->type),
            'current_waybill' => new WaybillData($this->current_waybill)
        ];
    }
}
