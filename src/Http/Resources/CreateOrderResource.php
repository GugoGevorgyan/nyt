<?php

declare(strict_types=1);

namespace Src\Http\Resources;

use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class CreateOrderResource
 * @package Src\Http\Resources
 */
class CreateOrderResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    #[ArrayShape([
        'coin' => 'mixed',
        'currency' => 'mixed',
        'initial' => 'mixed',
        'sitting_coin' => 'mixed'
    ])]
    public function toArray($request): array
    {
        return [
            'coin' => $this['coin'],
            'currency' => $this['currency'],
            'initial' => $this['initial'],
            'sitting_coin' => $this['sitting_coin'],
        ];
    }
}
