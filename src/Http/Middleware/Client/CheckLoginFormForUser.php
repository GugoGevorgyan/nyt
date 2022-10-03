<?php

declare(strict_types=1);

namespace Src\Http\Middleware\Client;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use function in_array;

/**
 * Class CheckLoginFormForUser
 * @package Src\Http\Middleware
 */
class CheckLoginFormForUser
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @noinspection MultipleReturnStatementsInspection
     */
    public function handle($request, Closure $next)
    {
        $guards = array_keys(config('auth.guards'));
        $auth_sessions = $request->getSession()->all();
        $current = [];

        foreach ($auth_sessions as $key => $value) {
            if (Str::is('*_web_*', $key)) {
                $guard = str_replace('login_', '', substr($key, 0, strpos($key, '_web_')).'_web');

                $current[] = $guard;
            }
        }

        foreach ($current as $cur) {
            $result = in_array($cur, $guards, true);

            if ($result) {
                if (('clients_web' === $cur) && \Str::contains(url()->current(), 'login-client')) {
                    return redirect()->route('homepage');
                }

                if (('system_workers_web' === $cur) && \Str::contains(url()->current(), 'app/worker')) {
                    return redirect()->route('get_dashboard_page');
                }

                if (('admin_corporate_web' === $cur) && \Str::contains(url()->current(), 'login-corporate')) {
                    return redirect()->route('admin_corporate_show_index');
                }

                if (('admin_super_web' === $cur) && \Str::contains(url()->current(), 'admin/super/login')) {
                    return redirect()->route('admin.super.dashboard');
                }
            }
        }

        return $next($request);
    }
}
