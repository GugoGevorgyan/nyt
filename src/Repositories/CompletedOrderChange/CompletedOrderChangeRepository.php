<?php

declare(strict_types=1);


namespace Src\Repositories\CompletedOrderChange;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\CompletedOrderChange;

/**
 * Class CompletedOrderChangeRepository
 * @package Src\Repositories\CompletedOrderChange
 */
class CompletedOrderChangeRepository extends BaseRepository implements CompletedOrderChangeContract
{
    /**
     * ComplaintStatusRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CompletedOrderChange::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('completed_order_change');
    }
}
