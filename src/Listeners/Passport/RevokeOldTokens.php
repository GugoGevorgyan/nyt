<?php

declare(strict_types=1);

namespace Src\Listeners\Passport;

use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Passport\Events\AccessTokenCreated;
use Src\Repositories\OauthAccessToken\OauthAccessTokenContract;

/**
 * Class RevokeOldTokens
 * @package Src\Listeners\Passport
 */
class RevokeOldTokens implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @param  OauthAccessTokenContract  $accessTokenContract
     */
    public function __construct(protected OauthAccessTokenContract $accessTokenContract)
    {
    }

    /**
     * Handle the event.
     *
     * @param  AccessTokenCreated  $event
     * @return void
     */
    public function handle(AccessTokenCreated $event): void
    {
        $tokens = $this->accessTokenContract
            ->where('id', '!=', $event->tokenId)
            ->where('user_id', '=', $event->userId)
            ->where('client_id', '=', $event->clientId)
            ->findAll();

        if (!$tokens->count()) {
            return;
        }

        foreach ($tokens as $token) {
            $token->refresh_token()->delete();
            $token->delete();
        }
    }
}
