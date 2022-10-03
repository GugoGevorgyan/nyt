<?php

declare(strict_types=1);


namespace Src\Repositories\OauthRefresh;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Oauth\Refresh;

/**
 * Class OauthRefreshRepository
 * @package Src\Repositories\OauthRefresh
 */
class OauthRefreshRepository extends BaseRepository implements OauthRefreshContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Refresh::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('oauth_refresh_tokens');
    }

}
