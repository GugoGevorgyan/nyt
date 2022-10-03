<?php

declare(strict_types=1);

namespace Src\Http\Resources\Terminal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PayBalanceResource
 * @package Src\Http\Resources\Terminal
 */
class PayBalanceResource extends JsonResource
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
            'all_balance' => $this['all_balance'],
        ];
    }
}
