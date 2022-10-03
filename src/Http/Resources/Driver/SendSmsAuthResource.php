<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SendSmsAuthResource
 * @package Src\Http\Resources\Driver
 */
class SendSmsAuthResource extends JsonResource
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
