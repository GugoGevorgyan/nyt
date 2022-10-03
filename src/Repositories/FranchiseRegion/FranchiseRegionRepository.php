<?php

declare(strict_types=1);


namespace Src\Repositories\FranchiseRegion;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Franchise\FranchiseRegion;

/**
 * Class FranchiseRegionRepository
 * @package Src\Repositories\FranchiseRegion
 */
class FranchiseRegionRepository extends BaseRepository implements FranchiseRegionContract
{
    /**
     * FranchiseRegionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(FranchiseRegion::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('franchise_regions');
    }
}
