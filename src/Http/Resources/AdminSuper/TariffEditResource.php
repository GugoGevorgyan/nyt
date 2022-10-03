<?php

declare(strict_types=1);

namespace Src\Http\Resources\AdminSuper;

use Src\Http\Resources\BaseResource;

class TariffEditResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
