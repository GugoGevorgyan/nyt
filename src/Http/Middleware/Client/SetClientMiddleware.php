<?php

declare(strict_types=1);

namespace Src\Http\Middleware\Client;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class SetClientMiddleware
 * @package Src\Http\Middleware
 */
class SetClientMiddleware
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Request
     */
    protected $getGuards;

    /**
     * Handle an incoming request.
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->request = $request;
        $this->getGuards = $request->getSession()->all();

        $guards = [];

        foreach ($this->getGuards as $key => $guard) {
            if (false !== stripos($key, 'login_clients_web')) {
                $guards[] = $key;
            }

            if (false !== stripos($key, 'login_before_clients_web')) {
                $guards[] = $key;
            }
        }

        if (\count($guards) > 1) {
            $guard = $this->iterateGuards($guards, 'login_clients_web');

            $this->deleteIgnoredGuard($guards, 'login_before_clients_web');
        } else {
            $guard = $this->iterateGuards($guards, $guards ? $guards[0] : 'login_before_clients_web');
        }

        Auth::shouldUse($guard);

        return $next($request);
    }

    /**
     * @param  array  $guards
     * @param  string  $name
     * @return string
     */
    protected function iterateGuards(array $guards, string $name = ''): string
    {
        $f_guard = '';

        foreach ($guards as $guard) {
            if (false !== stripos($guard, $name)) {
                $explode_web = explode('_web', $guard);
                $explode_login = explode('login_', $explode_web[0]);
                $f_guard = $explode_login[1].'_web';
            }
        }

        return $f_guard;
    }

    /**
     * @param  array  $guards
     * @param  string  $name
     * @return void
     */
    protected function deleteIgnoredGuard(array $guards, string $name = ''): void
    {
        foreach ($guards as $value) {
            if (false !== stripos($value, $name)) {
                $this->request->session()->forget($value);
                unset($this->getGuards[$value]);
            }
        }
    }
}
