<?php

declare(strict_types=1);


namespace Src\Repositories\FcmClient;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Oauth\FcmClient;

/**
 * Class FcmClientRepository
 * @package Src\Repositories\FcmClient
 */
class FcmClientRepository extends BaseRepository implements FcmClientContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(FcmClient::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('fcm_clients');
    }
}
