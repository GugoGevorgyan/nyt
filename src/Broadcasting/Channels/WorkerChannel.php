<?php

declare(strict_types=1);

namespace Src\Broadcasting\Channels;

use Src\Models\SystemUsers\SystemWorker;

/**
 * Class WorkerChannel
 * @package Src\Broadcasting\Channels
 */
class WorkerChannel
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
     * Authenticate the user's access to the channel.
     *
     * @param  SystemWorker  $worker
     * @param $worker_id
     * @param $franchise_id
     * @return bool
     */
    public function join(SystemWorker $worker, $worker_id, $franchise_id): bool
    {
        return !($worker->system_worker_id !== (int)$worker_id && $worker->franchise_id !== (int)$franchise_id);
    }
}
