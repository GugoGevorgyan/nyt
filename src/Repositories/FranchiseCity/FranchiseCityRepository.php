<?php

declare(strict_types=1);


namespace Src\Repositories\FranchiseCity;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Franchise\FranchiseCity;

/**
 * Class FranchiseCityRepository
 * @package Src\Repositories\FranchiseCity
 */
class FranchiseCityRepository extends BaseRepository implements FranchiseCityContract
{
    /**
     * FranchiseCityRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(FranchiseCity::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('franchise_cities');
    }
}
