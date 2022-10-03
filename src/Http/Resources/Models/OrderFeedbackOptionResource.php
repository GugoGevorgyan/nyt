<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class OrderFeedbackOptionResource
 * @package Src\Http\Resources
 */
class OrderFeedbackOptionResource extends BaseResource
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
            'option_id' => $this['order_feedback_option_id'],
            'option' => $this['option'],
            'name' => $this['name'],
            'completed' => $this['completed'],
            'canceled' => $this['canceled'],
            'assessment' => $this['assessment'],
        ];
    }
}
