<?php

declare(strict_types=1);

namespace Src\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use function in_array;

/**
 * Class AssignGuard
 * @package Src\Http\Middleware
 */
class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  null  $guard
     * @param  string  $redirectTo
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null, string $redirectTo = '/'): mixed
    {
        $conf_guards = [];

        foreach ((array)config('auth.guards') as $key => $guards) {
            $conf_guards[] = $key;
        }

        if (!in_array($guard, $conf_guards, true)) {
            return redirect($redirectTo);
        }

        session()->put('assigned_guard', $guard);

        return $next($request);
    }
}
