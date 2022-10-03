<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Car\CarClassesResource;
use Src\Http\Resources\Car\CarOptionResource;
use Src\Http\Resources\Car\CarResource;
use Src\Http\Resources\Models\FranchiseResource;

/**
 * Class DriverResource
 * @property mixed driver_id
 * @property mixed type_id
 * @property mixed name
 * @property mixed phone
 * @property mixed passport_serial
 * @property mixed email
 * @property mixed password
 * @property mixed role
 * @property mixed crewStatus
 * @property mixed car
 * @property mixed nickname
 * @property mixed graphic
 * @property mixed mean_assessment
 * @property mixed rating
 * @property mixed photo
 * @property mixed waybills
 * @property mixed push_key
 * @property mixed driver_info
 * @package Src\Http\Resources
 */
class DriverResource extends BaseResource
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
            'push_uid' => $this->fcm->key ?? '',
            'driver_email' => $this->driver_info->email,
            'name' => $this->driver_info->name,
            'surname' => $this->driver_info->surname,
            'patronymic' => $this->driver_info->patronymic,
            'photo' => $this->driver_info->photo,
            'graphic' => $this->graphic,
            'selected_class' => $this['selected_class']['ids'] ?? [],
            'azimuth' => $this['azimuth'] ?? '',
            'selected_option' => $this['selected_option']['ids'] ?? [],
            'classes' => CarClassesResource::collection($this->car->classes),
            'options' => CarOptionResource::collection($this->options),
            'addresses' => AddressFavoriteResource::collection($this->whenLoaded('addresses')),
            'car' => new CarResource($this->whenLoaded('car')),
            'current_franchise' => new FranchiseResource($this->whenLoaded('current_franchise')),
            'active_waybill' => $this->waybills->count() ? true : false,
        ];
    }
}
