<?php
declare(strict_types=1);

namespace Src\Http\Middleware\Modules;

use Closure;
use Illuminate\Http\Request;
use function define;
use function defined;

/**
 * Class CheckFranchiseModuleRoles
 * @package Src\Http\Middleware
 */
class CheckFranchiseModuleRoles
{
    /**
     * CheckFranchiseModules constructor.
     *
     */
    public function __construct()
    {
    }

    /**
     * @TODO STATUS TEST
     *
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param $module_name
     * @return mixed
     */
    public function handle($request, Closure $next, ...$module_name)
    {
        if (user()->is_admin) {
            return $next($request);
        }

        if (!defined('MODULE_NAMES')) {
            define('MODULE_NAMES', $module_name);
        }

        if (!user()->franchiseModuleHasRoles()) {
            return redirect()->route('homepage');
        }

        return $next($request);
    }
}
