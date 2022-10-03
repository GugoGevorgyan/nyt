<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\Bookkeeping;

use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;

/**
 * Class CompanyOrderResource
 * @package Src\Http\Resources\Worker\Accounting
 */
class TenantResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [];
    }
}
