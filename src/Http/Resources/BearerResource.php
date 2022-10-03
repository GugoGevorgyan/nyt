<?php

declare(strict_types=1);

namespace Src\Http\Resources;

use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class BearerResource
 * @property mixed token_type
 * @property mixed expires_in
 * @property mixed access_token
 * @property mixed refresh_token
 * @package Src\Http\Resources
 */
class BearerResource extends BaseResource
{
    public static $wrap = 'bearer';

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    #[ArrayShape([
        'expires_in' => 'mixed',
        'token_type' => 'mixed',
        'access_token' => 'mixed',
        'refresh_token' => 'mixed'
    ])]
    public function toArray($request): array
    {
        return [
            'expires_in' => $this['expires_in'],
            'token_type' => $this['token_type'],
            'access_token' => $this['access_token'],
            'refresh_token' => $this['refresh_token'],
        ];
    }
}
