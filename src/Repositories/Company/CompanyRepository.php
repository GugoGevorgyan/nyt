<?php

declare(strict_types=1);


namespace Src\Repositories\Company;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Corporate\Company;

/**
 * Class CompanyRepository
 * @package Src\Repositories\Company
 */
class CompanyRepository extends BaseRepository implements CompanyContract
{
    /**
     * CompanyRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Company::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('companies');
    }
}
