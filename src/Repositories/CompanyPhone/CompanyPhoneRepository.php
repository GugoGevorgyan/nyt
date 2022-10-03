<?php

declare(strict_types=1);


namespace Src\Repositories\CompanyPhone;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Corporate\CompanyPhone;

/**
 * Class CompanyPhoneRepository
 * @package Src\Repositories\CompanyPhone
 */
class CompanyPhoneRepository extends BaseRepository implements CompanyPhoneContract
{
    /**
     * CompanyRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CompanyPhone::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('company_phones');
    }
}
