<?php

declare(strict_types=1);

namespace Src\Http\Middleware\Driver;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Src\Exceptions\Lexcept;
use Src\Repositories\Driver\DriverContract;

/**
 *
 */
class DriverCheckWaybill
{
    /**
     * @param  DriverContract  $driverContract
     */
    public function __construct(protected DriverContract $driverContract)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @throws Lexcept
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $date_format = 'Y-m-d H:i:s';

        $driver = $this->driverContract
            ->whereHas('waybills', fn(Builder $query) => $query
                ->where('start_time', '<=', now()->format($date_format))
                ->where('end_time', '>=', now()->format($date_format))
                ->where('verified', '=', true)
                ->where('signed', '=', true)
            )
            ->find(get_user_id(), ['driver_id']);

        if (!$driver) {
            throw new Lexcept(trans('messages.driver_not_waybill'), 500);
        }

        return $next($request);
    }
}
