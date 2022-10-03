<?php

declare(strict_types=1);


namespace Src\Repositories\InitialOrderData;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderInitialData;

/**
 * Class InitialOrderDataRepository
 * @package Src\Repositories\InitialOrderData
 */
class InitialOrderDataRepository extends BaseRepository implements InitialOrderDataContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderInitialData::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('initial_order_data');
    }
}
