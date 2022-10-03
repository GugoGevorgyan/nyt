<?php

declare(strict_types=1);

namespace Src\Repositories\WorkerSession;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Role\WorkerRole;
use Src\Models\SystemWorker\WorkerSession;

/**
 *
 */
class WorkerSessionRepository extends BaseRepository implements WorkerSessionContract
{
    /**
     * WorkerGraphicRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(WorkerSession::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('worker_sessions');
    }
}
