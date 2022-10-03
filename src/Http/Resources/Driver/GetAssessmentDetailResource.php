<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class GetAssessmentDetailResource
 * @package Src\Http\Resources\Driver
 */
class GetAssessmentDetailResource extends BaseResource
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
            'name' => $this['name'],
            'option_id' => $this['option'],
            'assessment' => $this['assessment'],
        ];
    }
}
