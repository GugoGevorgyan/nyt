<?php

declare(strict_types=1);


namespace Src\Repositories\OrderStatus;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderStatus;

/**
 * Class OrderStatusRepository
 * @package Src\Repositories\OrderStatus
 */
class OrderStatusRepository extends BaseRepository implements OrderStatusContract
{
    /**
     * OrderStatusRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderStatus::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('order_statuses');
    }
}
