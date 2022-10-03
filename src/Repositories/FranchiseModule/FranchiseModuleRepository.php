<?php

declare(strict_types=1);


namespace Src\Repositories\FranchiseModule;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Franchise\FranchiseModule;

/**
 * Class FranchiseModuleRepository
 * @package Src\Repositories\FranchiseModule
 */
class FranchiseModuleRepository extends BaseRepository implements FranchiseModuleContract
{
    /**
     * FranchiseModuleRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(FranchiseModule::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('franchiseModule');
    }
}
