<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderRejectResource
 * @package Src\Http\Resources\Driver
 */
class OrderRejectResource extends JsonResource
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
            'message' => trans('messages.removed from your rating',['rating' => $this['rating']]),
            'rating' => $this['rating']
        ];
    }
}
