<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\CallCenter;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class OrdersPaginate extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return $this->resource;
    }
}
