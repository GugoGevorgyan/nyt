<?php

declare(strict_types=1);

namespace Src\Repositories\WorkerPermission;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Role\WorkerPermission;

/**
 * Class WorkerPermissionRepository
 * @package Src\Repositories\WorkerPermission
 */
class WorkerPermissionRepository extends BaseRepository implements WorkerPermissionContract
{
    /**
     * WorkerPermissionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(WorkerPermission::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('franchise_options');
    }
}
