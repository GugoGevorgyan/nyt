<?php

declare(strict_types=1);

namespace Src\Broadcasting\Channels;

use Src\Models\Client\Client;

/**
 * Class ClientApiBaseAuthChannel
 * @package Src\Broadcasting\Channels
 */
class ClientApiBaseAuthChannel
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
     * @param  Client  $client
     * @param $client_id
     * @param $phone
     * @return bool
     */
    public function join(Client $client, $client_id, $phone): bool
    {
        return !($client->client_id !== (int)$client_id && $client->phone !== (int)$phone);
    }
}
