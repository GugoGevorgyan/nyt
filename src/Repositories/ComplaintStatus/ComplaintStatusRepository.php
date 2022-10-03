<?php

declare(strict_types=1);


namespace Src\Repositories\ComplaintStatus;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Complaint\ComplaintStatus;

/**
 * Class ComplaintStatusRepository
 * @package Src\Repositories\ComplaintStatus
 */
class ComplaintStatusRepository extends BaseRepository implements ComplaintStatusContract
{
    /**
     * ComplaintStatusRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ComplaintStatus::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('complaint_statuses');
    }
}
