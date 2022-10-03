<?php

declare(strict_types=1);

namespace Src\Http\Resources\App;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class CoordinateResourse extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return $this['lat'] ?? $this[0] ?? null ? [
            'lat' => $this['lat'] ?? $this[0],
            'lut' => $this['lut'] ?? $this[1],
            'date' => $this['date'] ?? $this['created_at'] ?? '',
        ] : [];
    }
}
