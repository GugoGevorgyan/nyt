<?php

declare(strict_types=1);


namespace Src\Repositories\ComplaintFile;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Complaint\ComplaintFile;

/**
 * Class ComplaintFileRepository
 * @package Src\Repositories\ComplaintFile
 */
class ComplaintFileRepository extends BaseRepository implements ComplaintFileContract
{
    /**
     * ComplaintFileRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ComplaintFile::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('complaintFiles');
    }
}
