<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class DriverBlockResource extends BaseResource
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
            'id' => $this['driver_id'],
            'locked' => $this['locked'],
            'start' => Carbon::parse($this['start'])->format('d/m/y H:i'),
            'end' => Carbon::parse($this['end'])->format('d/m/y H:i'),
        ];
    }
}
