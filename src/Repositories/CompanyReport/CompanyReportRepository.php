<?php

declare(strict_types=1);


namespace Src\Repositories\CompanyReport;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Corporate\CompanyReport;

/**
 * Class CompanyReportRepository
 * @package Src\Repositories\CompanyReport
 */
class CompanyReportRepository extends BaseRepository implements CompanyReportContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CompanyReport::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('company_reports');
    }
}
