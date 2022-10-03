<?php

declare(strict_types=1);


namespace Src\Repositories\ClientDriverView;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Client\ClientTaxiView;

/**
 * Class ClientDriverViewRepository
 * @package Src\Repositories\ClientTaxiView
 */
class ClientDriverViewRepository extends BaseRepository implements ClientDriverViewContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ClientTaxiView::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('client_drivers_view');
    }

}
