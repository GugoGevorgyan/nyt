<?php

declare(strict_types=1);


namespace App\Configures\Passport;

use Laravel\Passport\Passport as BasePassport;
use Route;

/**
 * Class Passport
 * @package Src\Passport
 */
class Passport extends BasePassport
{
    /**
     * @var string
     */
    public static $cookie = 'nyt_token';

    /**
     * Binds the Passport routes into the controller.
     *
     * @param  callable|null  $callback
     * @param  array  $options
     * @return void
     */
    public static function routes($callback = null, array $options = []): void
    {
        $callback = $callback ?: static fn($router) => $router->all();

        $defaultOptions = [
            'prefix' => config('nyt.oauth_prefix'),
            'namespace' => '\Laravel\Passport\Http\Controllers',
        ];

        $options = array_merge($defaultOptions, $options);

        Route::group($options, static fn($router) => $callback(new PassportRouteRegister($router)));
    }
}
