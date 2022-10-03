<?php

declare(strict_types=1);

namespace Src\Http\Middleware\Driver;

use Closure;
use Illuminate\Http\Request;
use Src\Services\Driver\DriverServiceContract;

/**
 * Class CheckDriverData
 * @package Src\Http\Middleware
 */
class CheckDriverData
{
    /**
     * CheckDriverData constructor.
     * @param  DriverServiceContract  $driverContract
     */
    public function __construct(protected DriverServiceContract $driverContract)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        if (!$request->hasHeader('driver_id') || !$request->hasHeader('driver_username') || !$request->hasHeader('park_id')) {
            return response(['message' => 'Invalid Data'], 500);
        }

        return $next($request);
    }
}
