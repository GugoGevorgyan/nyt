<?php

declare(strict_types=1);

namespace Src\Http\Resources\App;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class ParkResources
 * @package Src\Http\Resources\App
 */
class ParkResources extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
