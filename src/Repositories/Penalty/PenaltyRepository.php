<?php

namespace Src\Repositories\Penalty;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Penalty\Penalty;

class PenaltyRepository extends BaseRepository implements PenaltyContract
{
    /**
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Penalty::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('penalties');
    }
}
