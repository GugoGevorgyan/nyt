<?php

declare(strict_types=1);

namespace Src\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

/**
 *
 */
class OrderPausesResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    #[ArrayShape([
        'lat' => 'mixed',
        'lut' => 'mixed',
        'paused' => 'string'
    ])]
    public function toArray($request): array
    {
        return [
            'lat' => $this['lat'],
            'lut' => $this['lut'],
            'paused' => Carbon::parse($this['paused'])->format('d/m/y H:i:s'),
        ];
    }
}
