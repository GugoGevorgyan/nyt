<?php

declare(strict_types=1);

namespace Src\Http\Middleware;

use Auth;
use Closure;
use Src\Repositories\Role\RoleContract;

use function define;
use function defined;

/**
 * Class DetectAuthedUser
 * @package Src\Http\Middleware
 */
class DetectAuthedUser
{
    /**
     * DetectAuthedUser constructor.
     * @param  RoleContract  $roleContract
     */
    public function __construct(protected RoleContract $roleContract)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param
     * Request  $request
     * @param  Closure  $next
     * @param  string  $guard_name
     * @return mixed
     */
    public function handle($request, Closure $next, string $guard_name = ''): mixed
    {
        if (!defined('USER_ID') && auth()->user()) {
            define('USER_ID', user()->{user()->getKeyName()});
        }

        if (!defined('FRANCHISE_ID') && (str_contains(Auth::guard()->getName(), 'system_workers_web') || str_contains(Auth::guard()->getName(), 'system_workers_api'))) {
            define('FRANCHISE_ID', user()->franchise_id);
        }

        return $next($request);
    }
}
