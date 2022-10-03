<?php

declare(strict_types=1);


namespace Src\Repositories\OrderOnWayRoad;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderOnWayRoad;

/**
 * Class OrderOnWayRoadRepository
 * @package Src\Repositories\OrderOnWayRoad
 */
class OrderOnWayRoadRepository extends BaseRepository implements OrderOnWayRoadContract
{
    /**
     * CarOptionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderOnWayRoad::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('order_on_way_roads');
    }
}
