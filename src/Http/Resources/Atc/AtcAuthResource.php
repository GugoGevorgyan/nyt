<?php

declare(strict_types=1);

namespace Src\Http\Resources\Atc;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class AtcAuthResource
 * @property mixed token_type
 * @property mixed expires_in
 * @package Src\Http\Resources
 */
class AtcAuthResource extends BaseResource
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
            'expires_in' => $this['expires_in'],
            'access_token' => $this['access_token'],
        ];
    }
}
