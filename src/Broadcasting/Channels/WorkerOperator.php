<?php

declare(strict_types=1);

namespace Src\Broadcasting\Channels;

use Src\Models\SystemUsers\SystemWorker;

/**
 * Class WorkerOperator
 * @package Src\Broadcasting\Channels
 */
class WorkerOperator
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
