<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\Mechanic;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class AuthResource
 * @package Src\Http\Resources\Worker\Mechanic
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
            'bearer' => [
                'type' => $this['token_type'],
                'expires' => $this['expires_in'],
                'token' => $this['access_token'],
            ],
            'worker' => [
                'id' => $this['system_worker_id'],
                'nickname' => $this['nickname'],
                'name' => $this['name'],
                'surname' => $this['surname'],
                'patronymic' => $this['patronymic'],
                'email' => $this['email'],
            ]
        ];
    }
}
