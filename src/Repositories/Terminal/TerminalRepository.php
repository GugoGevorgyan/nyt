<?php

declare(strict_types=1);


namespace Src\Repositories\Terminal;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Terminal\Terminal;

/**
 * Class TerminalRepository
 * @package Src\Repositories\Terminal
 */
class TerminalRepository extends BaseRepository implements TerminalContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Terminal::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('terminals');
    }
}
