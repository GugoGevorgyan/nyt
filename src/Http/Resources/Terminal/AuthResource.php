<?php

declare(strict_types=1);

namespace Src\Http\Resources\Terminal;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class AuthResource
 * @package Src\Http\Resources\Terminal
 */
class AuthResource extends BaseResource
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
            'type' => $this->get('token_type'),
            'expires' => $this->get('expires_in'),
            'token' => $this->get('access_token'),
            'refresh' => $this->get('refresh_token'),
        ];
    }
}
