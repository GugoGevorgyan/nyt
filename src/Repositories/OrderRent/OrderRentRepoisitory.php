<?php

declare(strict_types=1);


namespace Src\Repositories\OrderRent;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderRent;

/**
 * Class OrderScheduleRepoisitory
 * @package Src\Repositories\OrderRent
 */
class OrderRentRepoisitory extends BaseRepository implements OrderRentContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderRent::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('order_rent');
    }
}
