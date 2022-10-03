<?php

declare(strict_types=1);


namespace Src\Repositories\BeforeAuthClient;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Client\BeforeAuthClient;

/**
 * Class BeforeAuthClientRepository
 * @package Src\Repositories\BeforeAuthClient
 */
class BeforeAuthClientRepository extends BaseRepository implements BeforeAuthClientContract
{
    /**
     * CarClassRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(BeforeAuthClient::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('before_auth_clients');
    }
}
