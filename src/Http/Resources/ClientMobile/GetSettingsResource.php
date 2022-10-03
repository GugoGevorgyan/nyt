<?php

declare(strict_types=1);

namespace Src\Http\Resources\ClientMobile;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class GetSettingsResource
 * @package Src\Http\Resources\ClientMobile
 */
class GetSettingsResource extends BaseResource
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
            'show_cord' => $this['show_driver_my_coordinates'],
            'call' => $this['not_call'],
        ];
    }
}
