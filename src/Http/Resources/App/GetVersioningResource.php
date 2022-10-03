<?php

declare(strict_types=1);

namespace Src\Http\Resources\App;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class GetVersioningResource extends BaseResource
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
            'version' => $this['version'],
            'state' => $this['state'],
            'changed' => $this['updated_at'] ? Carbon::parse($this['updated_at'])->format('Y-m-d H:i') : '',
        ];
    }
}
