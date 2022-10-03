<?php

declare(strict_types=1);

namespace Src\Http\Resources\ClientMobile;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class LoginAuthResource
 * @package Src\Http\Resources\ClientMobile
 */
class LoginAuthResource extends BaseResource
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
            'client_id' => $this->get('client_id'),
            'type' => $this->get('type'),
            'expires' => $this->get('expires'),
            'token' => $this->get('token'),
        ];
    }
}
