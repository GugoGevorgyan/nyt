<?php

declare(strict_types=1);


namespace Src\Repositories\OperatorRejectCause;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OperatorRejectCause;

/**
 * Class OperatorRejectCauseRepository
 * @package Src\Repositories\OperatorRejectCause
 */
class OperatorRejectCauseRepository extends BaseRepository implements OperatorRejectCauseContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OperatorRejectCause::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('operator_reject_causes');
    }
}
