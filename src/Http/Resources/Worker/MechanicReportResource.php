<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class MechanicReportResource extends BaseResource
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
            'car_report_id' => $this->car_report_id,
            'emergency_lights' => $this->emergency_lights,
            'emergency_lights_comment' => $this->emergency_lights_comment,
            'headlights' => $this->headlights,
            'headlights_comment' => $this->headlights_comment,
            'tires' => $this->tires,
            'tires_comment' => $this->tires_comment,
            'engine' => $this->engine,
            'engine_comment' => $this->engine_comment,
            'images' => $this->images,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
