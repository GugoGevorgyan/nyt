<?php

declare(strict_types=1);


namespace Src\Repositories\Franchisee;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Franchise\Franchise;

/**
 * Class FranchiseRepository
 * @package Src\Repositories\Franchisee
 */
class FranchiseRepository extends BaseRepository implements FranchiseContract
{
    /**
     * FranchiseRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Franchise::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('franchisee');
    }


    /**
     * @param  array  $ids
     * @return int
     */
    public function dispatchingMinutes(array $ids = []): int
    {
        return $this
                ->whereIn('franchise_id', $ids)
                ->findFirst(['franchise_option_id', 'franchise_id', 'dispatching_minute'])
                ->dispatching_minute ?? 30;
    }
}
