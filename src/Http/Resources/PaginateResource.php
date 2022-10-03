<?php
/** @noinspection LongLine */

declare(strict_types=1);

namespace Src\Http\Resources;

use Illuminate\Http\Request;

/**
 * Class PaginateResource
 * @package Src\Http\Resources
 */
class PaginateResource extends BaseResource
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
            'sum' => $this->sum ?? $this['sum'] ?? '',
            'currency' => $this['currency'] ?? session('app_system.currency') ?? '',
            'current_page' => isset($this['current_page']) && (null === $this['current_page'] || $this['current_page']) ? $this['current_page'] : $this->currentPage(),
            'first_page_url' => isset($this['first_page_url']) && (null === $this['first_page_url'] || $this['first_page_url']) ? $this['first_page_url'] : $this->url(1),
            'from' => !$this['from'] && method_exists($this, 'firstItem') ? $this->firstItem() : $this['from'],
            'last_page' => $this['last_page'] && (null === $this['last_page'] || $this['last_page']) ? $this['last_page'] : $this->lastPage(),
            'last_page_url' => isset($this['last_page_url']) && (null === $this['last_page_url'] || $this['last_page_url']) ? $this['last_page_url'] : $this->url($this->lastPage()),
            'path' => isset($this['path']) && (null === $this['path'] || $this['path']) ? $this['path'] : $this->path(),
            'per_page' => isset($this['per_page']) && (null === $this['per_page'] || $this['per_page']) ? $this['per_page'] : $this->perPage(),
            'to' => !$this['to'] && method_exists($this, 'lastItem') ? $this->lastItem() : $this['to'],
            'total' => null !== $this['total'] || $this['total'] ? $this['total'] : $this->total(),
            'next_page_url' => null === $this['next_page_url'] || $this['next_page_url'] ? $this['next_page_url'] : $this->nextPageUrl(),
            'prev_page_url' => null === $this['prev_page_url'] || $this['prev_page_url'] ? $this['prev_page_url'] : $this->previousPageUrl(),

            '_payload' => $this->collectionClass::collection($this['data'] ?? $this->items()),
        ];
    }
}
