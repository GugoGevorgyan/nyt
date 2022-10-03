<?php

declare(strict_types=1);


namespace Src\Repositories\WorkerDispatcher;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\SystemUsers\WorkerDispatcher;

/**
 * Class WorkerDispatcherRepository
 * @package Src\Repositories\WorkerDispatcher
 */
class WorkerDispatcherRepository extends BaseRepository implements WorkerDispatcherContract
{
    /**
     * WorkerGraphicRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(WorkerDispatcher::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('worker_dispatchers');
    }
}
