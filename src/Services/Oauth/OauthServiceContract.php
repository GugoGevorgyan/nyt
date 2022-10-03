<?php

declare(strict_types=1);


namespace Src\Services\Oauth;


use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface OauthServiceContract
 * @package Src\Services\Oauth
 */
interface OauthServiceContract extends BaseContract
{
    /**
     * @param $client
     * @param  bool  $secret
     * @param  bool  $token
     * @param  bool  $refresh
     * @return Collection
     */
    public function clientTokensRevoke($client, bool $token = false, bool $refresh = false, bool $secret = false);

    /**
     * @param $client
     * @return mixed
     */
    public function clientTokensDelete($client);

    /**
     * @param $client
     * @param  bool  $revoked
     * @return Collection
     */
    public function getSecret($client, bool $revoked = false): Collection;

    /**
     * @param $id
     * @param $secret
     * @param $grant
     * @return bool
     */
    public function validateSecret($id, $secret, $grant): bool;

    /**
     * @param $secret
     * @return mixed
     */
    public function getSecretBySecret($secret);

    public function removeSecretWithToken($secret);
}
