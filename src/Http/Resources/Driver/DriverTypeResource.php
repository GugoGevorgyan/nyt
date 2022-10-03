<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class DriverWaybillResource
 * @package Src\Http\Resources
 */
class DriverTypeResource extends BaseResource
{
    public static $wrap = 'driver';

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'driver_type_id' => $this->driver_type_id,
            'type' => $this->type
        ];
    }
}
