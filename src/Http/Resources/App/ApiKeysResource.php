<?php

declare(strict_types=1);

namespace Src\Http\Resources\App;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class ApiKeysResource
 * @package Src\Http\Resources\App
 */
class ApiKeysResource extends BaseResource
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
            'y_geocode' => $this['geocode_key'] ?? '',
            'y_matrix' => $this['matrix_key'] ?? '',
            'y_route' => $this['route_key'] ?? '',
        ];
    }
}
