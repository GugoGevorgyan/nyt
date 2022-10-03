<?php

declare(strict_types=1);


namespace Src\Repositories\FranchisePhone;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Franchise\FranchisePhone;

/**
 * Class FranchisePhoneRepository
 * @package Src\Repositories\FranchisePhone
 */
class FranchisePhoneRepository extends BaseRepository implements FranchisePhoneContract
{
    /**
     * FranchisePhoneRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(FranchisePhone::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('franchise_phones');
    }
}
