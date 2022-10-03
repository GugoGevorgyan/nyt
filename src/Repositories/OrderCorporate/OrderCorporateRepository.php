<?php

declare(strict_types=1);


namespace Src\Repositories\OrderCorporate;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderCorporate;

/**
 * Class OrderCorporateRepository
 * @package Src\Repositories\OrderCorporate
 */
class OrderCorporateRepository extends BaseRepository implements OrderCorporateContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderCorporate::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('order_corporates');
    }
}
