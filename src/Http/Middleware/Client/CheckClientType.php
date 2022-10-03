<?php

declare(strict_types=1);

namespace Src\Http\Middleware\Client;

use Auth;
use Closure;
use Illuminate\Http\Request;

use function array_key_exists;
use function define;

/**
 * Class CheckClientType
 * @package Src\Http\Middleware
 */
class CheckClientType
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
        if (Auth::guest()) {
            $this->defCorp(false);
        } else {
            $has_corporate = array_key_exists('company_id', user()->getAttributes());
            $this->defCorp($has_corporate);
        }

        return $next($request);
    }

    /**
     * @param  bool  $values
     */
    protected function defCorp(bool $values): void
    {
        if (!\defined('CLIENT_IS_CORPORATE')) {
            define('CLIENT_IS_CORPORATE', $values);
        }
    }
}
