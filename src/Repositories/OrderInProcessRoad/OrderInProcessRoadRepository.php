<?php

declare(strict_types=1);


namespace Src\Repositories\OrderInProcessRoad;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderInProcessRoad;

/**
 * Class OrderInProcessRoadRepository
 * @package Src\Repositories\OrderInProcessRoad
 */
class OrderInProcessRoadRepository extends BaseRepository implements OrderInProcessRoadContract
{

    /**
     * CarOptionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderInProcessRoad::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('order_in_process_roads');
    }
}
