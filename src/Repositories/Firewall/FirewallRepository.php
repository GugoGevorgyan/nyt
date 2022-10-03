<?php

declare(strict_types=1);

namespace Src\Repositories\Firewall;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Secure\Firewall;

class FirewallRepository extends BaseRepository implements FirewallContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Firewall::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('firewall');
    }
}
