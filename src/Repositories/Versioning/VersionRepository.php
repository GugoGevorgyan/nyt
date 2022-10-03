<?php

declare(strict_types=1);

namespace Src\Repositories\Versioning;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Secure\Versioning;

/**
 *
 */
class VersionRepository extends BaseRepository implements VersionContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Versioning::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('versioning');
    }
}
