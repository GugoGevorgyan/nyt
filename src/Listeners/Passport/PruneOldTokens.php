<?php

declare(strict_types=1);

namespace Src\Listeners\Passport;

use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Passport\Events\RefreshTokenCreated;
use Src\Repositories\OauthAccessToken\OauthAccessTokenContract;
use Src\Repositories\OauthRefresh\OauthRefreshContract;

/**
 * Class PruneOldTokens
 * @package Src\Listeners\Passport
 */
class PruneOldTokens implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @param  OauthAccessTokenContract  $accessTokenContract
     * @param  OauthRefreshContract  $refreshContract
     */
    public function __construct(protected OauthAccessTokenContract $accessTokenContract, protected OauthRefreshContract $refreshContract)
    {
    }

    /**
     * Handle the event.
     *
     * @param  RefreshTokenCreated  $event
     * @return void
     */
    public function handle(RefreshTokenCreated $event): void
    {
    }
}
