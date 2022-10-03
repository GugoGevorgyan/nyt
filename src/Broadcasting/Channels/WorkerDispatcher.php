<?php

declare(strict_types=1);

namespace Src\Broadcasting\Channels;

use Src\Models\SystemUsers\SystemWorker;

/**
 * Class WorkerDispatcher
 * @package Src\Broadcasting\Channels
 */
class WorkerDispatcher
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param  SystemWorker  $user
     * @param $worker_id
     * @param $franchise_id
     * @return bool
     */
    public function join(SystemWorker $user, $worker_id, $franchise_id): bool
    {
        return true;
    }
}
