<?php

declare(strict_types=1);


namespace Src\Services\Oauth;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\AuthCodeRepository;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Bridge\ScopeRepository;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;
use ServiceEntity\BaseService;
use Src\Core\Enums\ConstProviders;
use Src\Core\Traits\OauthTrait;
use Src\Models\Oauth\Refresh;
use Src\Models\Oauth\Token;
use Src\Repositories\OauthAccessToken\OauthAccessTokenContract;
use Src\Repositories\OauthClient\OauthClientContract;

/**
 * Class OauthService
 * @package Src\Services\Oauth
 */
final class OauthService extends BaseService implements OauthServiceContract
{
    use OauthTrait;

    /**
     * OauthService constructor.
     * @param  OauthAccessTokenContract  $oauthClientRepository
     * @param  RefreshTokenRepository  $refreshRepository
     * @param  TokenRepository  $tokenRepository
     * @param  ClientRepository  $clientRepository
     * @param  OauthClientContract  $oauthClientContract
     * @param  AccessTokenRepository  $accessTokenRepository
     * @param  AuthCodeRepository  $authCodeRepository
     * @param  ScopeRepository  $scopeRepository
     * @param  UserRepository  $userRepository
     */
    public function __construct(
        protected OauthAccessTokenContract $oauthAccessContract,
        protected RefreshTokenRepository $refreshRepository,
        protected TokenRepository $tokenRepository,
        protected ClientRepository $clientRepository,
        protected OauthClientContract $oauthClientContract,
        protected AccessTokenRepository $accessTokenRepository,
        protected AuthCodeRepository $authCodeRepository,
        protected ScopeRepository $scopeRepository,
        protected UserRepository $userRepository
    ) {
    }

    /**
     * @inheritDoc
     */
    public function clientTokensRevoke($client, bool $token = false, bool $refresh = false, bool $secret = false)
    {
        $get_token = $this->getSecret($client);

        if (!$token) {
            return 'Invalid Client';
        }

        $token ? (new Token())->where('revoked', '=', 0)->where('id', '=', $get_token->get('token'))->update(['revoked' => 1]) : null;
        $refresh ? (new Refresh())->where('revoked', '=', 0)->where('access_token_id', $get_token->get('token'))->update(['revoked' => 1]) : null;
        $secret ? $this->oauthClientContract->updateSet(['revoked' => 1]) : null;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getSecret($client, bool $revoked = false): Collection
    {
        $token = $this->oauthAccessContract
            ->where('user_id', '=', $client->client_id)
            ->when(
                $revoked,
                static function (Builder $query) {
                    $query->where('revoked', '=', 0);
                }
            )
            ->whereHas(
                'client',
                static function (Builder $client) {
                    $client->where('provider', '=', ConstProviders::CLIENTS()->getValue());
                }
            )
            ->with('client:id,secret,provider')
            ->firstLatest('created_at');

        return $this->parseResult($instance, ['id', 'secret', 'token'], [$token->client->id, $token->client->secret, $token->id]);
    }

    /**
     * @param $client
     * @return string
     */
    public function clientTokensDelete($client)
    {
        $token = $this->getSecret($client);

        if (!$token) {
            return 'Invalid Client';
        }

        $this->oauthAccessContract->deletesBy('id', [$token->get('token')]);
        RefreshToken::where('access_token_id', '=', $token->get('token'))->delete();
        $this->oauthClientContract->deletesBy('id', [$token->get('id')]);

        return true;
    }

    /**
     * @param $id
     * @param $secret
     * @param $grant
     * @return bool
     */
    public function validateSecret($id, $secret, $grant): bool
    {
        return $this->oauthClientContract->validateClient($id, $secret, $grant);
    }

    /**
     * @param $secret
     * @return mixed
     */
    final public function getSecretBySecret($secret)
    {
        return $this->oauthClientContract->where('secret', '=', $secret)->findFirst(['id', 'secret']);
    }

    /**
     * @param $secret
     */
    public function removeSecretWithToken($secret)
    {
        $secret = $this->oauthClientContract->with('tokens')->where('secret', '=', $secret)->findFirst();

        if ($secret->tokens) {
            foreach ($secret->tokens as $token) {
                $token->delete();
            }
        }

        $secret->delete();
    }
}
