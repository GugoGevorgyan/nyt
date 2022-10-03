<?php

declare(strict_types=1);


namespace Src\Repositories\OrderWorkerComment;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Order\OrderWorkerComment;

/**
 * Class OrderWorkerCommentRepository
 * @package Src\Repositories\OrderWorkerComment
 */
class OrderWorkerCommentRepository extends BaseRepository implements OrderWorkerCommentContract
{
    /**
     * OrderStatusRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(OrderWorkerComment::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('order_worker_comments');
    }
}
