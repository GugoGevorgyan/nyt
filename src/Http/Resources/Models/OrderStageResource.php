<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class OrderStageResource
 * @package Src\Http\Resources
 */
class OrderStageResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $format = 'Y-m-d H:i';

        return [
            'stage_id' => $this['order_stage_id'] ?? '',
            'order_id' => $this['order_id'] ?? '',
            'accept' => $this['accept'] ?? '',
            'accepted' => $this['accepted'] ? Carbon::parse($this['accepted'])->format($format) : '',
            'on_way' => $this['on_way'] ?? '',
            'on_wayed' => $this['on_wayed'] ? Carbon::parse($this['on_wayed'])->format($format) : '',
            'in_place' => $this['in_place'] ?? '',
            'in_placed' => $this['in_placed'] ? Carbon::parse($this['in_placed'])->format($format) : '',
            'start' => $this['start'] ?? '',
            'started' => $this['started'] ? Carbon::parse($this['started'])->format($format) : '',
            'pauses' => $this['pauses'] ?? '',
            'end' => $this['end'] ?? '',
            'ended' => $this['ended'] ? Carbon::parse($this['ended'])->format($format) : ''
        ];
    }
}
