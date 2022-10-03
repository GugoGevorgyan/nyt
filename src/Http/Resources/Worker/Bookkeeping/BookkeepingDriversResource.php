<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\Bookkeeping;

use Src\Http\Resources\BaseResource;

class BookkeepingDriversResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'full_name' => $this['full_name'],
            'waybills_price' => $this['waybills_price'],
            'crashes_price' => $this['crashes_price'],
            'total_price' => $this['total_price'],
        ];
    }
}
