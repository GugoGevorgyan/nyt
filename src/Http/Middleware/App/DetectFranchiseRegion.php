<?php

declare(strict_types=1);

namespace Src\Http\Middleware\App;

use Closure;
use Illuminate\Http\Request;
use Src\Core\TransLiterate\EnglishToRussian;
use Src\Repositories\City\CityContract;
use Src\Repositories\Country\CountryContract;
use Src\Repositories\Franchisee\FranchiseContract;
use Src\Repositories\Region\RegionContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Region\RegionServiceContract;

/**
 * Class DetectFranchiseRegion
 * @package Src\Http\Middleware
 */
class DetectFranchiseRegion
{
    /**
     * DetectFranchiseRegion constructor.
     * @param  FranchiseContract  $franchiseContract
     * @param  CountryContract  $countryContract
     * @param  RegionContract  $regionContract
     * @param  CityContract  $cityContract
     * @param  GeocodeServiceContract  $geoService
     * @param  RegionServiceContract  $regionService
     */
    public function __construct(
        protected FranchiseContract $franchiseContract,
        protected CountryContract $countryContract,
        protected RegionContract $regionContract,
        protected CityContract $cityContract,
        protected GeocodeServiceContract $geoService,
        protected RegionServiceContract $regionService
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @noinspection MultipleReturnStatementsInspection
     */
    public function handle($request, Closure $next)
    {
        if (session()->exists('locality')) {
            return $next($request);
        }

        $coordinate = $this->getFromCoordinate($request);

        if (!$coordinate) {
            return $next($request);
        }

        $geocode = $this->geoService->getRightGeocode([$coordinate['lat'], $coordinate['lut']], true, true);
        $country = $this->countryContract->where('iso_2', '=', $geocode['country_code'])->with('franchisee_region')->findFirst();

        if (!$country && $country->franchisee_region->count() < 1) {
            return response(['message' => 'Вашей стране пока что не работает "Новое Желтое такси"', 'status' => 503]);
        }

        if (!$geocode['province'] && !$geocode['locality']) {
            return response(['message' => 'Invalid Data Country Location', 'status' => 503]);
        }

        $region = $this->regionContract->where('name', '=', $geocode['province'])->with('franchisee')->findFirst();

        if (!$region) {
            $name = $region->name ?? $country->name;
            return response(['message' => trans('messages.failed_region', ['name' => EnglishToRussian::transliterate($name)])], 503);
        }

//        if (!$region->franchisee && !$country->franchisee) {
//            return response(['message' => trans('messages.failed_region', ['name' => EnglishToRussian::transliterate($name)])], 503);
//        }

        if ($geocode['country_code'] !== $country->iso_2 && 1 > $region->franchisee->count()) {
            return response(['message' => 'Invalid Data Region Or City Location', 'status' => 503]);
        }

        $this->regionService->detectCountryRegions($geocode, $request->payment['company'] ?? null, true);

        return $next($request);
    }

    /**
     * @param  $request
     * @return array|null
     * @noinspection MultipleReturnStatementsInspection
     */
    protected function getFromCoordinate($request): ?array
    {
        $request = $request->all();

        if (isset($request['coordinates']) && $request['coordinates']['from']) {
            return ['lat' => $request['coordinates']['from'][0], 'lut' => $request['coordinates']['from'][1]];
        }

        if (isset($request['lat']) && $request['lat']) {
            return ['lat' => $request['lat'], 'lut' => $request['lut']];
        }

        if (isset($request['route'])) {
            return ['lat' => $request['route']['from'][0], 'lut' => $request['route']['from'][1]];
        }

        if (isset($request['from_coordinates'])) {
            return ['lat' => $request['from_coordinates'][0], 'lut' => $request['from_coordinates'][1]];
        }

        if (isset($request['order']['route'])) {
            return ['lat' => $request['order']['route']['from'][0], 'lut' => $request['order']['route']['from'][1]];
        }

        return null;
    }
}
