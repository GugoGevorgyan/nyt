<?php

declare(strict_types=1);


namespace Src\Repositories\Client;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Client\Client;

/**
 * Class ClientRepository
 * @package Src\Repositories\ClientMessage
 */
class ClientRepository extends BaseRepository implements ClientContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Client::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('clients');
    }
}
