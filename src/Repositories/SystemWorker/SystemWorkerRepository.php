<?php

declare(strict_types=1);


namespace Src\Repositories\SystemWorker;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class SystemWorkerRepository
 * @package Src\Repositories\SystemWorker
 */
class SystemWorkerRepository extends BaseRepository implements SystemWorkerContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(SystemWorker::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('system_workers');
    }
}
