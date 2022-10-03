<?php

declare(strict_types=1);

namespace Src\Repositories\ClientFavoriteDriver;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Client\ClientFavoriteDriver;

/**
 *
 */
class ClientFavoriteDriverRepository extends BaseRepository implements ClientFavoriteDriverContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ClientFavoriteDriver::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('client_favorite_driver');
    }
}
