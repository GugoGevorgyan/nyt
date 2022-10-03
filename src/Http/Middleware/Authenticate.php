<?php

declare(strict_types=1);

namespace Src\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Str;

/**
 * Class Authenticate
 * @package Src\Http\Middleware
 */
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string|null
     * @noinspection MultipleReturnStatementsInspection
     */
    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            if (Str::contains(url()->current(), 'app/worker')) {
                return route('system-show-login-form');
            }

            if (Str::contains(url()->current(), 'admin/super')) {
                return route('show.admin.super.login.form');
            }

            if (Str::contains(url()->current(), 'admin/corporate')) {
                return route('login_corporate');
            }

            if (Str::contains(url()->current(), '/')) {
                return route('homepage');
            }

            return route('login_client');
        }

        return null;
    }
}
