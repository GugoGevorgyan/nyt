<?php

declare(strict_types=1);

namespace Src\Http\Resources\Client;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class RegisterResource
 * @package Src\Http\Resources\Client
 */
class RegisterResource extends BaseResource
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
            'message' => trans('messages.Verification code sent to', ['phone' => $this->get('phone'), 'minute' => $this->get('expired')]),
            'phone' => $this->get('phone'),
            'expired' => $this->get('expired'),
        ];
    }
}
