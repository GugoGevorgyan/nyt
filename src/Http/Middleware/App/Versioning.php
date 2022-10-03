<?php

declare(strict_types=1);

namespace Src\Http\Middleware\App;

use Closure;
use Illuminate\Http\Request;
use Src\Core\Additional\Devicer;
use Src\Exceptions\Lexcept;

/**
 *
 */
class Versioning
{
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
        $device = (new Devicer());

        if (!$device->isiPhone() || !$device->isAndroidOS() || !$device->isDesktopMode() || !$request->hasHeader('auth_device')) {
            throw new Lexcept('Hack query. your device is not integrated for this request', 420);
        }

        return $next($request);
    }
}
