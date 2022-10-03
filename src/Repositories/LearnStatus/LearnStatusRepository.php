<?php

declare(strict_types=1);


namespace Src\Repositories\LearnStatus;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\LearnStatus;

/**
 * Class LearnStatusRepository
 * @package Src\Repositories\LearnStatus
 */
class LearnStatusRepository extends BaseRepository implements LearnStatusContract
{
    /**
     * LearnStatusRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(LearnStatus::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('learn_statuses');
    }
}
