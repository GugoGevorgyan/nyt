<?php

declare(strict_types=1);


namespace Src\Repositories\AddressRoute;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Monitor\AddressesRoute;

/**
 * Class AddressRouteRepository
 * @package Src\Repositories\AddressRoute
 */
class AddressRouteRepository extends BaseRepository implements AddressRouteContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(AddressesRoute::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('addresses_routes');
    }
}
