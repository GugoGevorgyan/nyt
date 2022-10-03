<?php

declare(strict_types=1);

namespace Src\Http\Middleware\Roles;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Src\Exceptions\Role\UnauthorizedException;

/**
 * Class RoleMiddleware
 * @package Src\Http\Middleware\Roles
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role): mixed
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roles = \is_array($role) ? $role : explode('|', $role);

        if (!Auth::user()->hasAnyRole($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }

        return $next($request);
    }
}
