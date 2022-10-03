<?php

declare(strict_types=1);

namespace Src\Http\Middleware\App;

use Closure;
use Illuminate\Http\Request;

/**
 * Class HttpsProtocol
 * @package Src\Http\Middleware
 */
class HttpsProtocol
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
        if (!$request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
