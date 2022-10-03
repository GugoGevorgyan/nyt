<?php

declare(strict_types=1);

namespace Src\Http\Middleware\SystemWorker;

use Closure;
use Illuminate\Http\Request;

/**
 * Class InSession
 * @package Src\Http\Middleware\SystemWorker
 */
class InSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        $user = user();

        if (!$user) {
            return redirect()->route('system-show-login-form');
        }

        $current_route = \Request::route()->getName();

        if (!$user->in_session && 'worker_dashboard_stop_session' !== $current_route && 'worker_dashboard_start_session' !== $current_route) {
            if ('GET' === \Request::method()) {
                return redirect()->route('system-show-login-form');
            }

            return response(['message' => 'SERVER ERROR'], 500);
        }

        return $next($request);
    }
}
