<?php
declare(strict_types=1);

namespace Src\Broadcasting\Channels;

use Src\Models\SystemUsers\SystemWorker;

/**
 * Class WorkerMessageChannel
 * @package Src\Broadcasting\Channels
 */
class WorkerMessageChannel
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
     * @param $room_id
     * @return array|bool
     */
    public function join(SystemWorker $user, $worker_id, $franchise_id)
    {
        return true;
//        $participant = app(ParticipantContract::class);
//
//        return $participant
//            ->where('room_id', '=', $room_id)
//            ->where('userable_id', '=', $user->{$user->getKeyName()})
//            ->exists();
    }
}
