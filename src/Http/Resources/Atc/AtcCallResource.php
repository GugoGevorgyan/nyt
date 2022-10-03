<?php

declare(strict_types=1);

namespace Src\Http\Resources\Atc;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class AtcAuthResource
 * @property mixed token_type
 * @property mixed expires_in
 * @package Src\Http\Resources
 */
class AtcCallResource extends BaseResource
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
            'type' => $this['type'],
            'extension' => $this['extension'],
        ];
    }
}
