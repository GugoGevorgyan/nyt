<?php

declare(strict_types=1);

namespace Src\Http\Middleware\Helpers;

use Closure;
use Illuminate\Http\Request;

/**
 * Class NumbersCleanSymbols
 * @package Src\Http\Middleware
 */
class NumbersCleanSymbols
{
    /**
     * @var array|string[]
     */
    private static array $needles = [
        'phone',
        '_phone',
        '-phone',
        'phone_',
        'phone-',
        'phones',
        'client',
        'passenger',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty($request->all())) {
            return $next($request);
        }

        $result = preg_nested_contains($request->all(), '/\D/', self::$needles);

        if (!empty($result['values'])) {
            if (!empty($result['keys'])) {
                $new_request = array_merge($request->all()[implode('', $result['keys'])], $result['values']);
                $request->merge([implode('', $result['keys']) => $new_request]);
            } else {
                $new_request = array_merge($request->all(), $result['values']);
                $request->merge($new_request);
            }
        }

        return $next($request);
    }
}
