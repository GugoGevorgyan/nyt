<?php
declare(strict_types=1);

namespace Src\Http\Middleware\Modules;

use Closure;
use Illuminate\Http\Request;
use function define;
use function defined;

/**
 * Class CheckFranchiseModules
 * @package Src\Http\Middleware
 */
class CheckFranchiseModules
{
    /**
     * CheckFranchiseModules constructor.
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
    public function handle($request, Closure $next, $module_name = null)
    {
        $module_name = explode('|', $module_name);

        if (!defined('MODULE_NAMES')) {
            define('MODULE_NAMES', $module_name);
        }

        if (!user()->franchiseHasModules($module_name)) {
            return redirect()->route('homepage');
        }

        return $next($request);
    }
}
