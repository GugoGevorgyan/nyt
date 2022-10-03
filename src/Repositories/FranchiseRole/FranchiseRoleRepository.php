<?php

declare(strict_types=1);

namespace Src\Repositories\FranchiseRole;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Role\FranchiseRole;

/**
 * Class FranchiseeRoleRepository
 * @package Src\Repositories\FranchiseeRole
 */
class FranchiseRoleRepository extends BaseRepository implements FranchiseRoleContract
{

    /**
     * FranchiseEntityRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(FranchiseRole::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('franchise_roles');
    }
}
