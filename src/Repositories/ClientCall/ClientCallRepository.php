<?php

declare(strict_types=1);


namespace Src\Repositories\ClientCall;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Client\ClientCall;

/**
 * Class ClientRepository
 * @package Src\Repositories\ClientCall
 */
class ClientCallRepository extends BaseRepository implements ClientCallContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ClientCall::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('client_calls');
    }
}
