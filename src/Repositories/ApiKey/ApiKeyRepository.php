<?php

declare(strict_types=1);


namespace Src\Repositories\ApiKey;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Secure\ApiKey;

/**
 * Class ApiKeyRepository
 * @package Src\Repositories\ApiKey
 */
class ApiKeyRepository extends BaseRepository implements ApiKeyContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ApiKey::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('api_keys');
    }
}
