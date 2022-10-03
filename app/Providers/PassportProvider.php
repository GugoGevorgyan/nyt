<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\HashCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;
use Laravel\Passport\Console\PurgeCommand;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider as BasePassportServiceProvider;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Src\Core\Repositories\PassportUserRepository;
use Src\Core\Repositories\RefreshTokenRepository;

/**
 * Class PassportProvider
 * @package App\Providers
 */
class PassportProvider extends BasePassportServiceProvider
{
    public function boot()
    {
//        Passport::hashClientSecrets();

        Passport::enableImplicitGrant();

        $this->commands(
            [
                InstallCommand::class,
                ClientCommand::class,
                KeysCommand::class,
                PurgeCommand::class,
                HashCommand::class
            ]
        );
    }

    /**
     * @return PasswordGrant
     * @throws BindingResolutionException
     */
    protected function makePasswordGrant()
    {
        $grant = new PasswordGrant(
            $this->app->make(PassportUserRepository::class),
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }

    /**
     * Create and configure a Refresh Token grant instance.
     *
     * @return RefreshTokenGrant
     * @throws BindingResolutionException
     */
    protected function makeRefreshTokenGrant()
    {
        $repository = $this->app->make(RefreshTokenRepository::class);

        return tap(
            new RefreshTokenGrant($repository),
            static function ($grant) {
                $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
            }
        );
    }
}
