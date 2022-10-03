<?php

declare(strict_types=1);


namespace Src\Repositories\FranchiseSubPhone;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Franchise\FranchiseSubPhone;

/**
 * Class FranchiseSubPhoneRepository
 * @package Src\Repositories\FranchiseSubPhone
 */
class FranchiseSubPhoneRepository extends BaseRepository implements FranchiseSubPhoneContract
{
    /**
     * FranchisePhoneRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(FranchiseSubPhone::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('franchise_sub_phones');
    }
}
