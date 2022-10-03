<?php

declare(strict_types=1);

namespace Src\Http\Resources\Car;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class CarResource
 * @property mixed car_id
 * @property mixed park_id
 * @property mixed current_driver_id
 * @property mixed car_class_id
 * @property mixed driver_ids
 * @property mixed mark
 * @property mixed model
 * @property mixed status
 * @property mixed year
 * @property mixed created_at
 * @package Src\Http\Resources
 */
class CarResource extends BaseResource
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
            'car_id' => $this['car_id'] ?? '',
            'park_id' => $this['park_id'] ?? '',
            'current_driver_id' => $this['current_driver_id'] ?? '',
            'car_class_id' => $this['car_class_id'] ?? '',
            'mark' => $this['mark'] ?? '',
            'model' => $this['model'] ?? '',
            'year' => $this['year'] ?? '',
            'status' => $this['status'] ?? '',
            'vin' => $this['vin_code'] ?? '',
            'speedometer' => $this['speedometer'] ?? '',
            'state_license_plate' => $this['state_license_plate'] ?? '',
            'garage_number' => $this['garage_number'] ?? '',
        ];
    }
}
