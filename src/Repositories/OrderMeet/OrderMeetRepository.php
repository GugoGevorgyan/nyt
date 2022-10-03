<?php

declare(strict_types=1);


namespace Src\Repositories\OrderMeet;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderMeet;

/**
 * Class OrderMeetRepository
 * @package Src\Repositories\OrderMeet
 */
class OrderMeetRepository extends BaseRepository implements OrderMeetContract
{
    /**
     * OrderMeetRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderMeet::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('order_meets');
    }
}
