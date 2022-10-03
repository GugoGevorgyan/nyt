<?php

declare(strict_types=1);

namespace Src\Http\Middleware\App;

use Closure;
use Illuminate\Http\Request;

/**
 * Class AppResponseInterceptor
 * @package Src\Http\Middleware\App
 */
class AppResponseInterceptor
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
        $response = $next($request);

        if (app()->environment('production')) {
            $response->headers->set('Content-Encoding', 'gzip');
            $response->headers->set('Accept-Encoding', 'gzip');
            $response->headers->set('Cache-Control', 'no-cache, must-revalidate');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, HEAD, OPTIONS, PUT, DELETE');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');
            $response->headers->set('Cache-Control', 'public');
        }

        return $response;
    }
}
