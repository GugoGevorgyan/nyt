<?php

declare(strict_types=1);


namespace Src\Repositories\ApiClient;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\SystemUsers\ApiClient;

/**
 * Class ApiClientRepository
 * @package Src\Repositories\ApiClient
 */
class ApiClientRepository extends BaseRepository implements ApiClientContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ApiClient::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('api_clients');
    }
}
