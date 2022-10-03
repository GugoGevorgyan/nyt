<?php

declare(strict_types=1);


namespace Src\Repositories\SuperAdmin;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\SystemUsers\SuperAdmin;

/**
 * Class SuperFranchisser
 * @package Src\Repositories\SuperFranchiser
 */
class SuperAdminRepository extends BaseRepository implements SuperAdminContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(SuperAdmin::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('super_admin');
    }
}
