<?php

declare(strict_types=1);


namespace Src\Repositories\OauthClient;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Oauth\Client;

/**
 * Class OauthClientRepository
 * @package Src\Repositories\OauthClient
 */
class OauthClientRepository extends BaseRepository implements OauthClientContract
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
            ->setRepositoryId('oauth_clients');
    }

}
