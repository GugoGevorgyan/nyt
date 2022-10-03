<?php

declare(strict_types=1);


namespace Src\Repositories\MechanicQuestion;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\CarReport\CarReportQuestion;

/**
 * Class MechanicQuestionRepository
 * @package Src\Repositories\CarReportQuestion
 */
class MechanicQuestionRepository extends BaseRepository implements MechanicQuestionContract
{
    /**
     * MechanicQuestionRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(CarReportQuestion::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('car_report_questions');
    }
}
