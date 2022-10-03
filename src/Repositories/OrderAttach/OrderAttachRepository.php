<?php

declare(strict_types=1);


namespace Src\Repositories\OrderAttach;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderAttach;

/**
 * Class OrderAttachRepository
 * @package Src\Repositories\OrderAttach
 */
class OrderAttachRepository extends BaseRepository implements OrderAttachContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderAttach::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('order_attaches');
    }
}
