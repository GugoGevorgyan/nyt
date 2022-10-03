<?php

declare(strict_types=1);

namespace Src\Http\Resources\Client;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class AddCardResource
 * @package Src\Http\Resources\Client
 */
class AddCardResource extends BaseResource
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
            'redirect' => $this['redirect'],
            'pareq' => $this['pareq'],
            'md' => $this['md'],
            'term_url' => $this['term_url'],
            'redirect_url' => 'https://google.com'
        ];
    }
}
