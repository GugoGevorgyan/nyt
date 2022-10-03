<?php

declare(strict_types=1);

namespace Src\Http\Middleware\App;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use function is_object;

/**
 * Class DebugBarTest
 * @package Src\Http\Middleware\App
 */
class DebugBarTest
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

        if (
            $response instanceof JsonResponse &&
            app()->bound('debugbar') &&
            app('debugbar')->isEnabled() &&
            is_object($response->getData()) &&
            'local' === config('app.env')
        ) {
            $response->setData(
                $response->getData(true) + [
                    '_debugbar' => app('debugbar')->getData(),
                ]
            );
        }

        return $response;
    }
}
