<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\GetFeedbackTypesResource;

/**
 * Class GetAssessmentResource
 * @package Src\Http\Resources\Driver
 */
class GetAssessmentResource extends BaseResource
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
            'completed_order_id' => $this['completed']['completed_order_id'],
            'feedback' => GetFeedbackTypesResource::collection($this['feedback']),
        ];
    }
}
