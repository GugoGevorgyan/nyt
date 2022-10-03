<?php

declare(strict_types=1);


namespace Src\Repositories\WorkerRole;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Role\WorkerRole;

/**
 * Class WorkerRoleRepository
 * @package Src\Repositories\WorkerRole
 */
class WorkerRoleRepository extends BaseRepository implements WorkerRoleContract
{
    /**
     * WorkerGraphicRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(WorkerRole::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('worker_role');
    }
}
