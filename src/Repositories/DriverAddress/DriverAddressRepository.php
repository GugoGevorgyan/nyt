<?php

declare(strict_types=1);


namespace Src\Repositories\DriverAddress;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\DriverAddress;

/**
 * Class DriverAddressRepository
 * @package Src\Repositories\DriverAddress
 */
class DriverAddressRepository extends BaseRepository implements DriverAddressContract
{
    /**
     * DriverRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(DriverAddress::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('driver_addresses');
    }
}
