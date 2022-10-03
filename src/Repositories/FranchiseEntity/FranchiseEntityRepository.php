<?php

declare(strict_types=1);


namespace Src\Repositories\FranchiseEntity;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Franchise\FranchiseEntity;

/**
 * Class FranchiseEntityRepository
 * @package Src\Repositories\FranchiseEntity
 */
class FranchiseEntityRepository extends BaseRepository implements FranchiseEntityContract
{
    /**
     * FranchiseEntityRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(FranchiseEntity::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('franchiseEntity');
    }
}
