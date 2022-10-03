<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class DriverInfoResource
 * @package Src\Http\Resources\Driver
 */
class DriverInfoResource extends BaseResource
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
            'driver_info_id' => $this['driver_info_id'] ?? '',
            'driver_id' => $this['driver_id'] ?? '',
            'name' => $this['name'] ?? '',
            'surname' => $this['surname'] ?? '',
            'patronymic' => $this['patronymic'] ?? '',
            'email' => $this['email'] ?? '',
            'passport_serial' => $this['passport_serial'] ?? '',
            'experience' => $this['experience'] ?? '',
            'address' => $this['address'] ?? '',
            'citizen' => $this['citizen'] ?? '',
            'photo' => $this['photo'] ?? '',
            'birthday' => $this['birthday'] ?? '',
            'kis_art' => $this['id_kis_art'] ?? '',
            'license_code' => $this['license_code'] ? (string)$this['license_code'] : '',
        ];
    }
}
