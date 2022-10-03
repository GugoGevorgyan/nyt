<?php

declare(strict_types=1);

namespace Src\Http\Middleware\Client;

use Closure;
use Illuminate\Http\Request;
use Src\Core\Additional\Devicer;
use Src\Core\Enums\ConstDeviceType;
use Src\Repositories\City\CityContract;
use Src\Repositories\Country\CountryContract;
use Src\Repositories\Region\RegionContract;

/**
 * Class DetectClientInfoMiddleware
 * @package Src\Http\Middleware
 */
class DetectClientInfoMiddleware
{
    /**
     * DetectClientInfoMiddleware constructor.
     * @param  CountryContract  $countryContract
     * @param  RegionContract  $regionContract
     * @param  CityContract  $cityContract
     */
    public function __construct(protected CountryContract $countryContract, protected RegionContract $regionContract, protected CityContract $cityContract)
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
        if (!user()) {
            return $next($request);
        }

        $device = '';
        $platform = (new Devicer())->device();
        $ip = geoip($request->ip());
        $user = user();

        if ((new Devicer())->isMobile()) {
            $device = ConstDeviceType::MOBILE()->getValue();
        }

        if ((new Devicer())->isPhone() && !(new Devicer())->isMobile()) {
            $device = ConstDeviceType::MOBILE_BROWSER()->getValue();
        }

        if ((new Devicer())->isDesktop()) {
            $device = ConstDeviceType::BROWSER()->getValue();
        }

        $ip_country = $ip->getAttribute('country');
        $ip_region = $ip->getAttribute('state_name');
        $ip_city = $ip->getAttribute('city');

        $country = $this->countryContract->where('name', '=', $ip_country)->findFirst(['name', 'country_id']);
        $region = $this->regionContract->where('name', '=', $ip_region)->findFirst(['name', 'region_id']);
        $city = $this->cityContract->where('name', '=', $ip_city)->findFirst(['name', 'city_id']);

        if (!$user) {
            return $next($request);
        }

        $model_name = $user->getMap();

        $user->session_info()->firstOrCreate( //@TODO FOR EVERY REQUEST UPDATE INFO CHANGE updateOrCreate TO firstOrCreate
            ['clientable_id' => $user->{$user->getKeyName()}, 'clientable_type' => $model_name],
            [
                'country_id' => $country?->country_id,
                'region_id' => $region?->region_id,
                'city_id' => $city?->city_id,
                'ip_address' => $request->ip(),
                'platform' => $platform,
                'device' => $device,
                'mobile' => (new Devicer())->isMobile()
            ]
        );

        return $next($request);
    }
}
