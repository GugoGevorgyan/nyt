<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class OrderFeedbackResource
 * @package Src\Http\Resources
 */
class OrderFeedbackResource extends BaseResource
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
            'feedback_id' => $this['order_feedback_id'] ?? '',
            'feedback_option_id' => $this['feedback_option_id'] ?? '',
            'order_id' => $this['order_id'] ?? '',
            'text' => $this['text'] ?? '',
            'assessment' => $this['assessment'] ?? '',
            'writable' => $this['writable'],
            'readable' => $this['readable'],
            'option' => $this['option'] ? new OrderFeedbackOptionResource($this['option']) : [],
        ];
    }
}
