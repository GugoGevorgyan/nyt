<?php

declare(strict_types=1);


namespace Src\Repositories\Complaint;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Complaint\Complaint;

/**
 * Class ComplaintRepository
 * @package Src\Repositories\Complaint
 */
class ComplaintRepository extends BaseRepository implements ComplaintContract
{
    /**
     * ComplaintRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Complaint::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('complaints');
    }
}
