<?php

declare(strict_types=1);

namespace Src\Http\Resources\ClientMobile;

use Illuminate\Http\Resources\Json\JsonResource;

class GetGuestInitialData extends JsonResource
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
