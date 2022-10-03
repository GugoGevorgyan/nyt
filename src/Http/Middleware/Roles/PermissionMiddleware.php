<?php

declare(strict_types=1);

namespace Src\Http\Middleware\Roles;

use Closure;
use Illuminate\Http\Request;
use Src\Exceptions\Role\UnauthorizedException;

use function is_array;

/**
 * Class PermissionMiddleware
 * @package Src\Http\Middleware\Roles
 */
class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission): mixed
    {
        if (app('auth')->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $perm) {
            if (app('auth')->user()->getPermissionNames()->contains($perm)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
