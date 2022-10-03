<?php
declare(strict_types=1);

namespace Src\Http\Middleware\Modules;

use Closure;
use Illuminate\Http\Request;

/**
 * Class CheckUserModulesHasFranchise
 * @package Src\Http\Middleware\Modules
 */
class CheckFranchiseHasUserModules
{
    /**
     * CheckFranchiseModules constructor.
     *
     */
    public function __construct()
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param $module_name
     * @return mixed
     */
    public function handle($request, Closure $next, $module_name)
    {
        if (user()->is_admin) {
            return $next($request);
        }

        if (!user()->hasModule(explode('|', $module_name))) {
            return redirect()->route('homepage');
        }

        return $next($request);
    }
}
