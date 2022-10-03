<?php

declare(strict_types=1);

namespace Src\Http\Resources\Models;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class ComplaintsResource
 * @package Src\Http\Resources
 */
class ComplaintsResource extends BaseResource
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
            'complaint' => $this['complaint'] ?? '',
            'created_at' => $this['created_at'] ?? '',
            'subject' => $this['subject'] ?? '',
            'recipient' => $this['recipient'] ?? '',
            'writer' => $this['writer'] ?? '',
        ];
    }
}
