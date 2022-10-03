<?php

declare(strict_types=1);

namespace Src\Http\Resources;

use Illuminate\Http\Request;

/**
 * Class GetFeedbackTypesResource
 * @property mixed option
 * @property mixed name
 * @package Src\Http\Resources\Driver
 */
class GetFeedbackTypesResource extends BaseResource
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
            'option' => $this['option'],
            'feedback' => $this['name'],
        ];
    }
}
