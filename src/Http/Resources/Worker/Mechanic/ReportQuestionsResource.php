<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\Mechanic;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class ReportQuestionsResource
 * @package Src\Http\Resources\Worker\Mechanic
 */
class ReportQuestionsResource extends BaseResource
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
            'id' => $this['question_id'],
            'question' => trans($this['question']),
        ];
    }
}
