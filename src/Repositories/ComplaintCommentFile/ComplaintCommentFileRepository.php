<?php

declare(strict_types=1);


namespace Src\Repositories\ComplaintCommentFile;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Complaint\ComplaintCommentFile;

/**
 * Class ComplaintCommentFileRepository
 * @package Src\Repositories\ComplaintCommentFile
 */
class ComplaintCommentFileRepository extends BaseRepository implements ComplaintCommentFileContract
{
    /**
     * ComplaintCommentFileRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ComplaintCommentFile::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('complaintCommentFiles');
    }
}
