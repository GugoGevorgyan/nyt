<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class PaymentTypeResource
 * @package Src\Http\Resources
 */
class PaymentTypeResource extends BaseResource
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
            'id' => $this['payment_type_id'],
            'type' => $this['type'],
            'name' => $this['name'],
            'text' => trans('messages.'.$this['text']),
        ];
    }
}
