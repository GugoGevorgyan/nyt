<?php

declare(strict_types=1);

namespace Src\Http\Resources\App;

use Illuminate\Http\Request;
use JsonException;
use Src\Http\Resources\BaseResource;

/**
 * Class CompaniesResource
 * @package Src\Http\Resources\App
 */
class CompaniesResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     * @throws JsonException
     */
    public function toArray($request): array
    {
        return [
            'id' => $this['company_id'],
            'name' => $this['name'],
            'car_classes' => array_map('\intval', (array)json_decode($this['car_classes_ids'], true, 512, JSON_THROW_ON_ERROR)['ids']),
        ];
    }
}
