<?php

declare(strict_types=1);


namespace Src\Repositories\CorporateClient;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Corporate\CorporateClient;

/**
 * Class CorporateClientRepository
 * @package Src\Repositories\CorporateClient
 */
class CorporateClientRepository extends BaseRepository implements CorporateClientContract
{
    /**
     * CorporateClientRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CorporateClient::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('corporate_clients');
    }
}
