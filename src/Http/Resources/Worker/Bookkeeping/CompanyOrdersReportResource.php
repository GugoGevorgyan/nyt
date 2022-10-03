<?php

declare(strict_types=1);

namespace Src\Http\Resources\Worker\Bookkeeping;

use Src\Http\Resources\BaseResource;

class CompanyOrdersReportResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'code' => $this['company_id'],
            'name' => $this['name'],
            'cost' => $this['completed_sum_cost'] ?? 0,
            'vat' => '',
            'total_with_VAT' => $this['completed_sum_cost'] ?? 0,
        ];
    }
}
