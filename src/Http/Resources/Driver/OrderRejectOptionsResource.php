<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class OrderRejectOptionsResource
 * @package Src\Http\Resources\Driver
 */
class OrderRejectOptionsResource extends BaseResource
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
            'option' => $this['option'],
            'name' => $this['name'],
        ];
    }
}
