<?php

declare(strict_types=1);


namespace Src\Repositories\CanceledOrder;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\CanceledOrder;

/**
 * Class CanceledOrderRepository
 * @package Src\Repositories\CanceledOrder
 */
class CanceledOrderRepository extends BaseRepository implements CanceledOrderContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CanceledOrder::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('canceled_orders');
    }
}
