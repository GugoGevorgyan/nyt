<?php

declare(strict_types=1);


namespace Src\Repositories\Role;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Role\Role;

/**
 * Class SystemWorkerRoleRepository
 * @package Src\Repositories\WorkerRole
 */
class RoleRepository extends BaseRepository implements RoleContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Role::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('roles');
    }
}
