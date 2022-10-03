<?php
declare(strict_types=1);

namespace Src\Http\Middleware\Roles;

use Src\Exceptions\Role\UnauthorizedException;
use Auth;
use Closure;
use Illuminate\Http\Request;
use function is_array;

/**
 * Class RoleOrPermissionMiddleware
 * @package Src\Http\Middleware\Roles
 */
class RoleOrPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roleOrPermission): mixed
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if (!Auth::user()->hasAnyRole($rolesOrPermissions) && !Auth::user()->hasAnyPermission($rolesOrPermissions)) {
            throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);
        }

        return $next($request);
    }
}
