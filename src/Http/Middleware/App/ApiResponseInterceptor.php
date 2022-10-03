<?php

declare(strict_types=1);

namespace Src\Http\Middleware\App;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use function strlen;

/**
 *
 */
class ApiResponseInterceptor
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return ResponseFactory|Application|Response
     */
    public function handle(Request $request, Closure $next): ResponseFactory|Application|Response
    {
        $response = $next($request);
        $content = $response->content();
        $data = gzencode($content, (int)ini_get('zlib.output_compression_level'));

        return response($data)->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET',
            'Content-type' => 'application/json; charset=utf-8',
            'Charset' => 'utf-8',
            'Content-Length' => strlen($data),
            'Content-Encoding' => 'gzip'
        ]);
    }
}
