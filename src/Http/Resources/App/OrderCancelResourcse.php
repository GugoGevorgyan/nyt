<?php

declare(strict_types=1);

namespace Src\Http\Resources\App;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 *
 */
class OrderCancelResourcse extends BaseResource
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
            'aborted_id' => $this['id'],
            'options' => $this['options'] ?? [],
            'cancel_fee' => (bool)$this['cancel_price'],
            'cancel_price' => $this['cancel_price'],
        ];
    }
}
