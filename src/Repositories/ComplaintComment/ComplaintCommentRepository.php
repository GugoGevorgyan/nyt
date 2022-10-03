<?php

declare(strict_types=1);


namespace Src\Repositories\ComplaintComment;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Complaint\ComplaintComment;

/**
 * Class ComplaintCommentRepository
 * @package Src\Repositories\ComplaintComment
 */
class ComplaintCommentRepository extends BaseRepository implements ComplaintCommentContract
{
    /**
     * ComplaintCommentRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ComplaintComment::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('complaintComments');
    }
}
