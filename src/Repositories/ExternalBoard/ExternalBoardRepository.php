<?php

declare(strict_types=1);

namespace Src\Repositories\ExternalBoard;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Driver\ExternalBoard;

/**
 *
 */
class ExternalBoardRepository extends BaseRepository implements ExternalBoardContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ExternalBoard::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('external_boards');
    }
}
