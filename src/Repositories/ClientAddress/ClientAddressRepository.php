<?php

declare(strict_types=1);


namespace Src\Repositories\ClientAddress;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Client\ClientAddress;

/**
 * Class ClientAddressRepository
 * @package Src\Repositories\ClientAddress
 */
class ClientAddressRepository extends BaseRepository implements ClientAddressContract
{
    /**
     * ClientAddressRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ClientAddress::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('client_addresses');
    }
}
