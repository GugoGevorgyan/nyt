<?php

declare(strict_types=1);


namespace Src\Repositories\Franchisee;


use Repository\Contracts\BaseRepositoryContract;

/**
 * Interface FranchiseContract
 * @package Src\Repositories\Franchisee
 */
interface FranchiseContract extends BaseRepositoryContract
{
    /**
     * @param  array  $ids
     * @return int
     */
    public function dispatchingMinutes(array $ids = []): int;
}
