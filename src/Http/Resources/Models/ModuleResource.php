<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class ModuleResource
 * @property mixed name
 * @property mixed slug_name
 * @property mixed description
 * @package Src\Http\Resources
 */
class ModuleResource extends BaseResource
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
            'name' => $this->name,
            'slug_name' => $this->slug_name,
            'description' => $this->description,
        ];
    }
}
