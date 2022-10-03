<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class CityResources
 * @package Src\Http\Resources\AdminSuper
 */
class CityResources extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'name' => $this['name'],
            'country' => $this['country']['name'],
            'iso_2' => $this['country']['iso_2'],
            'created_at' => $this['created_at'],
        ];
    }
}
