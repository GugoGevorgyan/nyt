<?php

declare(strict_types=1);

namespace Src\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Http\Resources\BaseResource;

/**
 * Class PayCardResource
 * @package Src\Http\Resources\Client
 */
class PayCardResource extends BaseResource
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
            'id' => $this['client_pay_card_id'],
            'number' => $this['card_number'],
            'expiration_date' => $this['card_expiration'],
            'cvc_number' => $this['card_cvc'],
        ];
    }
}
