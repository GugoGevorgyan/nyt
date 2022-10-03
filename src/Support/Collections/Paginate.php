<?php

declare(strict_types=1);


namespace Src\Support\Collections;

use Closure;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class PaginateCollection
 * @package Src\Support
 */
class Paginate
{
    /**
     * @return Closure
     */
    public function __invoke(): Closure
    {
        return function (int $perPage = 15, string $pageName = 'page', int $page = null, int $total = null, array $options = []): LengthAwarePaginator {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            $results = $this->forPage($page, $perPage)->values();

            $total = $total ?: $this->count();

            $options += [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ];

            return new LengthAwarePaginator($results, $total, $perPage, $page, $options);
        };
    }
}
