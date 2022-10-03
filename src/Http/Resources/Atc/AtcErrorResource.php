<?php

declare(strict_types=1);

namespace Src\Http\Resources\Atc;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class AtcErrorResource
 * @package Src\Http\Resources
 */
class AtcErrorResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'message' => $this['message']
        ];
    }
}
