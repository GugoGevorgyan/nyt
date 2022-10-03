<?php

declare(strict_types=1);

namespace Src\Broadcasting\Channels;

use Src\Models\Client\BeforeAuthClient;
use Src\Repositories\BeforeAuthClient\BeforeAuthClientContract;
use Src\Repositories\Client\ClientContract;

/**
 * Class BeforeClientBaseChannel
 * @package Src\Broadcasting\Channels
 */
class BeforeClientBaseChannel
{
    /**
     * Create a new channel instance.
     *
     * @param  BeforeAuthClientContract  $beforeClientContract
     */
    public function __construct(protected BeforeAuthClientContract $beforeClientContract)
    {
    }

    /**w
     * Authenticate the user's access to the channel.
     *
     * @param  BeforeAuthClient  $client
     * @param $before_client_id
     * @param $hash
     * @return bool
     */
    public function join(BeforeAuthClient $client, $before_client_id, $hash): bool
    {
        $get_client = $this->beforeClientContract->find($before_client_id, ['before_auth_client_id', 'hash_name', 'hash']);

        if (!$get_client) {
            return false;
        }

        if ($get_client->hash === $hash) {
            return true;
        }

        return false;
    }
}
