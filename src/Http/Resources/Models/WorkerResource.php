<?php

declare(strict_types=1);


namespace Src\Http\Resources\Models;


use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class WorkerResource
 * @package Src\Http\Resources
 */
class WorkerResource extends BaseResource
{

    public static $wrap = 'worker';

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $waybill = [];
        if (isset($this['waybill']) && !empty($this['waybill'])) {
            $waybill = WaybillResource::collection($this['waybill']);
        }

        return [
            'system_worker_id' => $this['system_worker_id'] ?? '',
            'worker_name' => $this['name'] ?? '',
            'worker_surname' => $this['surname'] ?? '',
            'worker_nickname' => $this['nickname'] ?? '',
            'worker_phone' => $this['phone'] ?? '',
            'worker_email' => $this['email'] ?? '',
            'worker_description' => $this['description'] ?? '',
            'worker_prize' => $this['prize'] ?? '',
            'worker_salary' => $this['salary'] ?? '',
            'worker_rating' => $this['rating'] ?? '',
            'franchisee' => new FranchiseResource($this['franchise']) ?: [],
            'waybills' => $waybill,
        ];
    }
}
