<?php

declare(strict_types=1);


namespace Src\Services\Region;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use ServiceEntity\BaseService;
use Src\Repositories\Airport\AirportContract;
use Src\Repositories\Area\AreaContract;
use Src\Repositories\City\CityContract;
use Src\Repositories\Country\CountryContract;
use Src\Repositories\Metro\MetroContract;
use Src\Repositories\RailwayStation\RailwayStationContract;
use Src\Repositories\Region\RegionContract;

/**
 * Class RegionService
 * @package Src\Services\Region
 */
final class RegionService extends BaseService implements RegionServiceContract
{
    /**
     * RegionService constructor.
     * @param  RegionContract  $regionContract
     * @param  CountryContract  $countryContract
     * @param  CityContract  $cityContract
     * @param  AreaContract  $areaContract
     */
    public function __construct(
        protected RegionContract $regionContract,
        protected CountryContract $countryContract,
        protected CityContract $cityContract,
        protected AreaContract $areaContract,
        protected RailwayStationContract $stationContract,
        protected AirportContract $airportContract,
        protected MetroContract $metroContract,
    ) {
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getRegionPaginate($request): LengthAwarePaginator
    {
        $per_page = $request['per-page'] ?: 10;
        $page = $request['page'] ?: 1;
        $search = ($request['search'] && 'null' !== $request['search']) ? $request['search'] : '';

        return $this->regionContract
            ->with('country')
            ->when($search, fn(Builder $query) => $query
                ->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('iso_2', 'LIKE', '%'.$search.'%')
                ->orWhereHas('country', fn($q) => $q->where('name', 'LIKE', '%'.$search.'%')))
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @inheritDoc
     */
    public function getAllRegions()
    {
        return $this->regionContract->findAll(['region_id', 'country_id', 'name', 'iso_2']) ?: [];
    }

    /**
     * @param $country_id
     * @return array|Collection
     */
    #[ArrayShape([
        'regions' => 'array|\Illuminate\Support\Collection',
        'phoneMask' => 'mixed'
    ])]
    public function getRegionsByCountry($country_id = null): array|Collection
    {
        $regions = $this->regionContract
            ->when($country_id, fn($query) => $query->where('country_id', '=', $country_id))
            ->findAll(['region_id', 'country_id', 'name', 'iso_2', 'cord'])
            ?: [];

        $phone_mask = $this->countryContract->where('country_id', '=', $country_id)->findFirst(['country_id', 'phone_mask']);

        return ['regions' => $regions, 'phoneMask' => $phone_mask ? $phone_mask['phone_mask'] ?? '' : ''];
    }

    /**
     * @param $request
     * @return object|null
     */
    public function createRegion($request): ?object
    {
        return $this->regionContract->create($request->only(['name', 'country_id', 'iso_2']));
    }

    /**
     * @param $request
     * @param $region_id
     * @return mixed
     */
    public function updateRegion($request, $region_id)
    {
        return $this->regionContract->updateSet($request->only(['name', 'country_id', 'iso_2']));
    }

    /**
     * @param $region_id
     * @return mixed
     */
    public function deleteRegion($region_id)
    {
        return $this->regionContract->delete($region_id);
    }

    /**
     * @param $request
     * @return mixed
     */
    public function destroyMultiple($request)
    {
        return $this->regionContract->deletesBy('region_id', $request->ids);
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'from_country_id' => 'mixed',
        'from_region_id' => 'mixed',
        'from_city_id' => 'mixed',
        'from_franchise_ids' => 'array',
        'company' => 'null'
    ])] public function detectCountryRegions($geocode, $company = null, bool $in_session = false): array
    {
        $country = $this->countryContract->where('iso_2', '=', $geocode['country_code'])->with('franchisee')->findFirst();
        $region = $this->regionContract->where('name', '=', $geocode['province'])->with('franchisee')->findFirst();
        $city = $this->cityContract->where('name', '=', $geocode['locality'])->with('franchisee')->findFirst();

        $franchise_ids = [];

        if ($country && $country->franchisee->count() > 0) {
            foreach ($country->franchisee as $franchise) {
                $franchise_ids[] = $franchise->franchise_id;
            }
        }

        if ($region && $region->franchisee->count() > 0) {
            foreach ($region->franchisee as $franchise) {
                $franchise_ids[] = $franchise->franchise_id;
            }
        }

        if ($city && $city->franchisee->count() > 0) {
            foreach ($city->franchisee as $franchise) {
                $franchise_ids[] = $franchise->franchise_id;
            }
        }

        $in_session
            ? $this->saveOrderSes($country->country_id, $region->region_id, $franchise_ids, $city->city_id ?? null, (int)$company)
            : null;

        return [
            'from_country_id' => $country->country_id,
            'from_region_id' => $region->region_id,
            'from_city_id' => $city->city_id ?? null,
            'from_franchise_ids' => array_unique($franchise_ids),
            'company' => $company
        ];
    }

    /**
     * @param  int  $country_id
     * @param  int  $region_id
     * @param  array  $franchise_ids
     * @param  int|null  $city_id
     * @param  int|null  $company
     */
    protected function saveOrderSes(int $country_id, int $region_id, array $franchise_ids, int $city_id = null, int $company = null): void
    {
        session()->flash('order', [
            'from_country_id' => $country_id,
            'from_region_id' => $region_id,
            'from_city_id' => $city_id ?? null,
            'from_franchise_ids' => array_unique($franchise_ids),
            'company' => $company
        ]);
    }

    /**
     * @param $region_id
     * @return mixed
     */
    public function getCitiesByRegion($region_id): Collection
    {
        return $this->cityContract
            ->where('region_id', '=', $region_id)
            ->findAll(['city_id', 'region_id', 'name']) ?: collect();
    }

    /**
     * @param $region_ids
     * @return mixed
     */
    public function getCitiesByRegions($region_ids): Collection
    {
        return $this->cityContract
            ->whereIn('region_id', $region_ids)
            ->findAll(['city_id', 'region_id', 'name']) ?: collect();
    }

    /**
     * @param $city
     * @return object|null
     */
    public function getTransportsPoints($city): ?object
    {
        return $this->cityContract
            ->where('name', '=', $city)
            ->with([
                'airports' => fn($query) => $query->select('*'),
                'metros' => fn($query) => $query->select('*'),
                'railways' => fn($query) => $query->select('*'),
            ])
            ->findFirst(['city_id', 'name']);
    }

    /**
     * @return mixed|void
     */
    public function getAllCountries()
    {
        return $this->countryContract->where('phone_mask', '!=', '')->findAll(['country_id', 'name']) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function citiesPager(array $payload): LengthAwarePaginator
    {
        return $this->cityContract
            ->when($payload['countries'],
                fn(Builder $query) => $query->whereHas('country',
                    fn(Builder $query) => $query->whereIn('country_id', explode(',', $payload['countries'])))
            )
            ->with(['country' => fn($query) => $query->select(['country_id', 'name', 'iso_2'])])
            ->paginate((int)$payload['per_page'], ['city_id', 'country_id', 'name', 'created_at'], 'page', (int)$payload['page']);
    }

    /**
     * @inheritdoc
     */
    public function getAreas(int $region_id = null, int $country_id = null): Collection
    {
        if ($region_id) {
            return $this->areaContract
                ->whereHas('regions')
                ->whereJsonContains('region->ids', $region_id)
                ->findAll();
        }

        if ($country_id) {
            return $this->areaContract
                ->whereHas('regions', fn($query) => $query->where('country_id', '=', $country_id))
                ->findAll();
        }

        return $this->areaContract->orderBy('area_id', 'desc')->findAll() ?: collect();
    }

    /**
     * @param $area_id
     * @return object|null
     */
    public function getRegionsByArea($area_id): ?object
    {
        return $this->areaContract->where('area_id', '=', $area_id)->findFirst(['region']);
    }

    /**
     * @inheritDoc
     */
    public function getTransports(): array
    {
        $railway_stations = $this->stationContract->findAll(['railway_station_id', 'city_id', 'name', 'input', 'address', 'lat', 'lut']);
        $airports = $this->airportContract->findAll(['airport_id', 'city_id', 'name', 'terminal', 'address', 'lat', 'lut']);
        $metros = $this->metroContract->findAll(['metro_id', 'city_id', 'name', 'input', 'address', 'lat', 'lut']);

        return [$railway_stations, $airports, $metros];
    }
}
