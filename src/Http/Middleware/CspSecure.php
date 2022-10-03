<?php

declare(strict_types=1);

namespace Src\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CspSecure
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

//        $response->headers->set(
//            'Content-Security-Policy',
//            "default-src 'self'; style-src 'self';
//            script-src 'self' ajax.cloudflare.com;
//            script-src 'self' ajax.cloudflare.com;
//            script-src 'self' 'unsafe-inline'
//            ",
//        );

        return $response;
    }
}
