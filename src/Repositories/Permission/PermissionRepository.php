<?php

declare(strict_types=1);


namespace Src\Repositories\Permission;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Role\Permission;

/**
 * Class SystemWorkerPermissionRepository
 * @package Src\Repositories\Permission
 */
class PermissionRepository extends BaseRepository implements PermissionContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Permission::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('permissions');
    }
}
