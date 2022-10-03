<?php

declare(strict_types=1);


namespace Src\Repositories\OrderType;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderType;

/**
 * Class OrderTypeRepository
 * @package Src\Repositories\OrderType
 */
class OrderTypeRepository extends BaseRepository implements OrderTypeContract
{
    /**
     * OrderTypeRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderType::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('order_types');
    }
}
