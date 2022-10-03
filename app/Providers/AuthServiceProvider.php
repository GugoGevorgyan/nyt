<?php

declare(strict_types=1);

namespace App\Providers;

use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Configures\Passport\Passport;
use App\Configures\Passport\PassportRouteRegister;
use Src\Models\Driver\DriverCandidate;
use Src\Models\Franchise\Franchise;
use Src\Models\Oauth\AuthCode;
use Src\Models\Oauth\Client;
use Src\Models\Oauth\PersonalAccessClient;
use Src\Models\Oauth\Refresh;
use Src\Models\Oauth\Token;
use Src\Policies\DriverCandidatePolicy;
use Src\Policies\FranchiseeCrudPolicy;

/**
 * Class AuthServiceProvider
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Franchise::class => FranchiseeCrudPolicy::class,
        DriverCandidate::class => DriverCandidatePolicy::class,
    ];

    /**
     *
     */
    public function register(): void
    {
        Passport::ignoreMigrations();
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        $this->passport();

        Passport::tokensExpireIn(now()->addMonths(24));

        Passport::refreshTokensExpireIn(now()->addMonths(24));

        Passport::personalAccessTokensExpireIn(now()->addMonths(1));

        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);

        Passport::personalAccessClient();

        Gate::before(fn($user, $ability) => $user->is_admin ? true : null);
    }


    protected function passport(): void
    {
        Passport::routes(fn(PassportRouteRegister $router) => $router->all());

        Passport::useTokenModel(Token::class);
        Passport::useClientModel(Client::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);
        Passport::useRefreshTokenModel(Refresh::class);
    }
}
