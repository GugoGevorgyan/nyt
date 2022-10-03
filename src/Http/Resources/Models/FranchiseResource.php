<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * @property int franchise_id
 * @property string name
 */
class FranchiseResource extends BaseResource
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
            'franchise_id' => $this->franchise_id,
            'name' => $this->name,
        ];
    }
}
