<?php

declare(strict_types=1);


namespace Src\Repositories\WorkerOperator;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\SystemUsers\WorkerOperator;

/**
 * Class WorkerOperatorRepository
 * @package Src\Repositories\WorkerOperator
 */
class WorkerOperatorRepository extends BaseRepository implements WorkerOperatorContract
{
    /**
     * WorkerGraphicRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(WorkerOperator::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('worker_operators');
    }
}
