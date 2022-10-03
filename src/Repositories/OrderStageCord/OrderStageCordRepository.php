<?php

declare(strict_types=1);


namespace Src\Repositories\OrderStageCord;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderStageCord;

/**
 * Class OrderStageCordRepository
 * @package Src\Repositories\OrderStageCord
 */
class OrderStageCordRepository extends BaseRepository implements OrderStageCordContract
{
    /**
     * CarOptionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderStageCord::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('order_stages_cord');
    }
}
