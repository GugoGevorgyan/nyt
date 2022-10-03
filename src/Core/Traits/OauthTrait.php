<?php

declare(strict_types=1);


namespace Src\Core\Traits;

use Artisan;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use JsonException;
use Laravel\Passport\Token;
use Lcobucci\JWT\Parser;

use function define;
use function defined;

/**
 * Trait OauthTrait
 * @package Src\Traits
 */
trait OauthTrait
{
    /**
     * @param $user_data
     * @return mixed
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getOauthTokenByPasswordGrantType($user_data): mixed
    {
        $get_client_pass_token = $this->client
            ->post(config('nyt.app_url').'/'.config('nyt.oauth_prefix').'/token', [
                'form_params' => [
                    'client_id' => $user_data['client_id'],
                    'client_secret' => $user_data['client_secret'],
                    'username' => $user_data['username'],
                    'password' => $user_data['password'],
                    'guard' => $user_data['guard'],
                    'provider' => $user_data['provider'] ?? '',
                    'grant_type' => 'password',
                ],
                'headers' => [
                    'Content-Encoding' => 'gzip',
                    'Cache-Control' => 'no-cache, must-revalidate',
                    'Accept-Encoding' => 'gzip, deflate, sdch, br',
                ]
            ]);

        if (200 !== $get_client_pass_token->getStatusCode()) {
            return null;
        }

        return json_decode($get_client_pass_token->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param $user_data
     * @return mixed
     * @throws GuzzleException
     * @throws JsonException
     */
    public function refreshOauthToken($user_data): mixed
    {
        $get_client_pass_token = $this->client->post(
            config('nyt.app_url').'/'.config('nyt.oauth_prefix').'/token',
            [
                'form_params' => [
                    'client_id' => $user_data->client_id,
                    'client_secret' => $user_data->client_secret,
                    'refresh_token' => $user_data->refresh_token,
                    'guard' => $user_data->guard,
                    'grant_type' => 'refresh_token',
                ],
                'headers' => [
                    'Content-Encoding' => 'gzip',
                    'Cache-Control' => 'no-cache, must-revalidate',
                    'Accept-Encoding' => 'gzip, deflate, sdch, br'
                ]
            ]
        );

        if (200 !== $get_client_pass_token->getStatusCode()) {
            return null;
        }

        return json_decode($get_client_pass_token->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param $client_id
     * @param $client_secret
     * @param  array|string[]  $scope
     * @return mixed
     * @throws GuzzleException
     * @throws JsonException
     */
    public function createTokenBySecret($client_id, $client_secret, array $scope = ['*']): mixed
    {
        $personal_client = $this->client->post(
            config('nyt.app_url').'/'.config('nyt.oauth_prefix').'/token',
            [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'scope' => [implode(',', $scope)],
                ],
                'headers' => [
                    'Content-Encoding' => 'gzip',
                    'Cache-Control' => 'no-cache, must-revalidate',
                    'Accept-Encoding' => 'gzip, deflate, sdch, br'
                ]
            ]
        );

        if (200 !== $personal_client->getStatusCode()) {
            return null;
        }

        return json_decode($personal_client->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK);
    }

    /**
     * @param  string  $client_payload
     * @param  string  $provider
     * @return array
     */
    public function createClientSecret(string $client_payload, string $provider = 'clients'): array
    {
        if (!defined('STDIN')) {
            define('STDIN', fopen('php://stdin', 'rb'));
        }

        Artisan::call(
            'passport:client',
            [
                '--password' => true,
                '--redirect_uri' => config('nyt.app_url'),
                '--name' => $client_payload,
                '--provider' => $provider
            ]
        );
        $result = Artisan::output();

        $filter_one = strpos($result, 'Client ID:');
        $filter_two = trim(preg_replace('/\s\s+/', '', substr($result, $filter_one + 11)));
        $filtered = explode('Client secret:', $filter_two);

        /** @noinspection OffsetOperationsInspection */
        $id = isset($filtered[0]) ? (int)preg_replace('/\n/', '', $filtered[0]) : '';
        /** @noinspection OffsetOperationsInspection */
        $secret = isset($filtered[1]) ? (string)preg_replace('/\s+/', '', $filtered[1]) : '';

        return compact('id', 'secret');
    }

    /**
     * @param  string  $name
     * @return array
     */
    public function createClientIdSecret(string $name = 'ATC'): array
    {
        if (!defined('STDIN')) {
            define('STDIN', fopen('php://stdin', 'rb'));
        }

        Artisan::call(
            'passport:client',
            [
                '--client' => true,
                '--redirect_uri' => config('nyt.app_url'),
                '--name' => $name,
            ]
        );
        $result = Artisan::output();

        /** @noinspection OffsetOperationsInspection */
        $id = explode(' Client secret:', trim(preg_replace('/\s\s+/', ' ', substr($result, strpos($result, 'Client ID:') + 11))))[0];
//        $secret = trim(preg_replace('/\s\s+/', ' ', substr($result, strpos($result, 'Client secret:') + 15)));
        $secret = 'lEWuT1UvJYMXbIBoSPJkjiisWtUHtFfWgGz5rdVasVkkugAATWKCGV8PUhrjzLj5';// @todo fix atc auth

        return compact('id', 'secret');
    }

    /**
     * @param  string  $token
     * @return array
     */
    public function getClientIdByToken(string $token): array
    {
        $request = (new Request());
        $request->setMethod('POST');
        $request->header('authorization', 'Bearer '.$token);
        $request->headers->set('authorization', 'Bearer '.$token);

        $tokenId = (new Parser())->parse($request->bearerToken())->getClaim('jti');
        $client = (new Token())->find($tokenId)->client;

        $client_id = $client->id;
        $client_secret = $client->secret;

        return compact('client_id', 'client_secret');
    }
}
