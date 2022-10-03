<?php

declare(strict_types=1);


namespace Src\Repositories\CompletedOrder;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\CompletedOrder;

/**
 * Class CompletedOrderRepository
 * @package Src\Repositories\CompletedOrder
 */
class CompletedOrderRepository extends BaseRepository implements CompletedOrderContract
{
    /**
     * ComplaintStatusRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CompletedOrder::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('completed_orders');
    }
}
