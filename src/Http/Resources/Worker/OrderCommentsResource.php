<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Models\WorkerResource;

/**
 * Class OrderCommentsResource
 * @package Src\Http\Resources\Worker
 */
class OrderCommentsResource extends BaseResource
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
            'order_worker_comment_id' => $this['text'],
            'text' => $this['text'],
            'created_at' => $this['created_at'],
            'worker' => $this['worker'] ? new WorkerResource($this['worker']) : []
        ];
    }
}
