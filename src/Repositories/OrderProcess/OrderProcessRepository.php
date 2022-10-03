<?php

declare(strict_types=1);


namespace Src\Repositories\OrderProcess;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderProcess;

/**
 * Class OrderProcessRepository
 * @package Src\Repositories\OrderProcess
 */
class OrderProcessRepository extends BaseRepository implements OrderProcessContract
{
    /**
     * CarOptionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderProcess::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('order_processes');
    }
}
