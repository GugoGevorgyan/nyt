<?php

declare(strict_types=1);


namespace Src\Repositories\Address;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Monitor\Address;

/**
 * Class AddressRepository
 * @package Src\Repositories\Address
 */
class AddressRepository extends BaseRepository implements AddressContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Address::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('addresses');
    }
}
