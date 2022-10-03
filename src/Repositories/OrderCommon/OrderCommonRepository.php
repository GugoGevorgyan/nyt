<?php

declare(strict_types=1);


namespace Src\Repositories\OrderCommon;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderCommon;

/**
 * Class OrderCommonRepository
 * @package Src\Repositories\OrderCommon
 */
class OrderCommonRepository extends BaseRepository implements OrderCommonContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderCommon::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('orders_common');
    }
}
