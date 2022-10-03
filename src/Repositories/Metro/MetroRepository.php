<?php

declare(strict_types=1);


namespace Src\Repositories\Metro;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\TransportStations\Metro;

/**
 * Class MetroRepository
 * @package Src\Repositories\Metro
 */
class MetroRepository extends BaseRepository implements MetroContract
{
    /**
     * MessageRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Metro::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('metro');
    }
}
