<?php

declare(strict_types=1);


namespace Src\Repositories\CarReportImage;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\CarReport\CarReportImage;

/**
 * Class CarReportImageRepository
 * @package Src\Repositories\CarReportImage
 */
class CarReportImageRepository extends BaseRepository implements CarReportImageContract
{
    /**
     * CarReportRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CarReportImage::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('car_report_images');
    }
}
