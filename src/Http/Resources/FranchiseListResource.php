<?php

declare(strict_types=1);

namespace Src\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Franchise\Franchise;

/**
 * Class FranchiseListResource
 * @property mixed collection
 * @property mixed modules
 * @package Src\Http\Resources
 */
class FranchiseListResource extends ResourceCollection
{
    /**
     * @var ServiceModel
     */
    public $collects = Franchise::class;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'current_page' => $this->currentPage(),
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'last_page' => $this->lastPage()
        ];
    }
}
