<?php

declare(strict_types=1);

namespace Src\Repositories\ExternalOrder;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\ExternalOrder;

/**
 *
 */
class ExternalOrderRepository extends BaseRepository implements ExternalOrderContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ExternalOrder::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('external_orders');
    }
}
