<?php

declare(strict_types=1);

namespace Src\Http\Resources\Terminal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class DriverAuthResource
 * @package Src\Http\Resources\Terminal
 */
class DriverAuthResource extends JsonResource
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
            'token_type' => $this['token_type'],
            'expires_in' => ($this['expires_in'] / 3600) / 24,
            'access_token' => $this['access_token'],
        ];
    }
}
