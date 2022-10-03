<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class ClientResource extends BaseResource
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
            'client_id' => $this['client_id'],
            'name' => $this['name'],
            'surname' => $this['surname'],
            'patronymic' => $this['patronymic'],
            'email' => $this['email'],
            'phone' => $this['phone'],
            'mean_assessment' => $this['mean_assessment'],
            'online' => $this['online'],
            'in_order' => $this['in_order'],
            'created_at' => $this['created_at'] ? Carbon::parse($this['created_at'])->format('H:i d/m/y') : '',
        ];
    }
}
