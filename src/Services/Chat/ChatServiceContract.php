<?php
declare(strict_types=1);


namespace Src\Services\Chat;


use ServiceEntity\Contract\BaseContract;

/**
 * Interface ChatServiceContract
 * @package Src\Services\WorkerChat
 */
interface ChatServiceContract extends BaseContract
{
    /**
     * @return mixed
     */
    public function getRooms();

    /**
     * @param $sender_id
     * @param $room_id
     * @param $participant_ids
     * @param $message_data
     * @return mixed
     */
    public function messageSave($sender_id, $room_id, $message_data);

    /**
     * @param $room_id
     * @param $user_id
     * @return mixed
     */
    public function getConversation($room_id, $user_id);

    /**
     * @param $client_id
     * @param string $msg
     * @return void
     */
    public function clientBroadwayDriver($client_id, string $msg = ''): void;

    /**
     * @param $driver_id
     * @param  string  $msg
     * @return void
     */
    public function driverBroadwayClient($driver_id, string $msg = ''): void;
}
