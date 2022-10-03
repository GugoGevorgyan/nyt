<?php

declare(strict_types=1);

namespace Src\Repositories\OrderCrossing;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\CompletedOrderCrossing;

/**
 *
 */
class OrderCrossingRepository extends BaseRepository implements OrderCrossingContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CompletedOrderCrossing::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('completed_orders_crossing');
    }
}
