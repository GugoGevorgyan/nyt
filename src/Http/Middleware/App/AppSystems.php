<?php

declare(strict_types=1);

namespace Src\Http\Middleware\App;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Src\Repositories\Country\CountryContract;

/**
 * Class AppSystems
 * @package Src\Http\Middleware\App
 */
class AppSystems
{
    /**
     * @param  CountryContract  $countryContract
     */
    public function __construct(protected CountryContract $countryContract)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed // @todo
     */
    public function handle($request, Closure $next): mixed
    {
        $ip_country = geoip($request->ip());

        if ('Azerbaijan' === $ip_country->country) {
            exit('<pre>Dick</pre>');
        }

        $country = $this->countryContract
            ->where('iso_2', '=', $ip_country->iso_code)
            ->findFirst(['country_id', 'iso_2', 'phone_mask']);

        if (!$country) {
            $country = $this->countryContract
                ->where('iso_2', '=', Str::upper(config('app.locale')))
                ->findFirst(['country_id', 'iso_2', 'phone_mask']);
        }

        if ($country) {
            session()->now('app_system', [
                'country' => $ip_country->country,
                'iso_code' => $ip_country->iso_code,
                'postal_code' => $ip_country->postal_code,
                'timezone' => $ip_country->timezone,
                'currency' => $ip_country->currency,
                'mask' => $country->phone_mask,
            ]);
        }

        App::setLocale(/*$ip_country->iso_code ? strtolower($ip_country->iso_code) : */ config('nyt.language'));

        return $next($request);
    }
}
