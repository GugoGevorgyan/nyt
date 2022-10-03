<?php
declare(strict_types=1);

namespace Src\Broadcasting\Channels;

use Src\Models\Client\BeforeAuthClient;
use Src\Models\Client\Client;
use Src\Repositories\Client\ClientContract;

/**
 * Class ClientWebAuthChannel
 * @package Src\Broadcasting\Channels
 */
class ClientWebBaseAuthChannel
{
    /**
     * @var ClientContract
     */
    protected ClientContract $clientContract;

    /**
     * Create a new channel instance.
     *
     * @param  ClientContract  $clientContract
     */
    public function __construct(ClientContract $clientContract)
    {
        $this->clientContract = $clientContract;
    }

    /**w
     * Authenticate the user's access to the channel.
     *
     * @param  Client  $client
     * @param $client_id
     * @param $phone
     * @return bool
     */
    public function join(Client $client, $client_id, $phone): bool
    {
        $get_client = $this->clientContract->find($client_id, ['client_id', 'phone', 'online']);

        if (!$get_client) {
            return false;
        }

        if ($get_client->phone === $phone && $client->phone === $phone) {
            $get_client->update(['online' => 1]);
            return true;
        }

        $get_client->update(['online' => 0]);
        return false;
    }
}
