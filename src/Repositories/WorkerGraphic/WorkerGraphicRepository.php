<?php

declare(strict_types=1);


namespace Src\Repositories\WorkerGraphic;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\SystemUsers\WorkerGraphic;

/**
 * Class WorkerGraphicRepository
 * @package Src\Repositories\WorkerGraphic
 */
class WorkerGraphicRepository extends BaseRepository implements WorkerGraphicContract
{
    /**
     * WorkerGraphicRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(WorkerGraphic::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('workers_graphic');
    }
}
