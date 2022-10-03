<?php

declare(strict_types=1);


namespace Src\Repositories\AdminCorporate;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Corporate\AdminCorporate as AdminCorporateModel;

/**
 * Class AdminCorporate
 * @package Src\Repositories\AdminCorporate
 */
class AdminCorporateRepository extends BaseRepository implements AdminCorporateContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(AdminCorporateModel::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('admin_corporates');
    }
}
