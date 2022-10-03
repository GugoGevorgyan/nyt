<?php

declare(strict_types=1);


namespace Src\Repositories\OauthAccessToken;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Oauth\Token;

/**
 * Class OauthAccessTokenRepository
 * @package Src\Repositories\OauthAccessToken
 */
class OauthAccessTokenRepository extends BaseRepository implements OauthAccessTokenContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Token::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('oauth_access_tokens');
    }

}
