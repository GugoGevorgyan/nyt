<?php

declare(strict_types=1);


namespace Src\Services\SuperAdmin;


use Hash;
use Illuminate\Database\Eloquent\Builder;
use ServiceEntity\BaseService;
use Src\Repositories\Airport\AirportContract;
use Src\Repositories\Metro\MetroContract;
use Src\Repositories\RailwayStation\RailwayStationContract;
use Src\Repositories\SuperAdmin\SuperAdminContract;

/**
 * Class SuperFranchiserService
 * @package Src\Services\SuperFranchiser
 */
class SuperAdminService extends BaseService implements SuperAdminServiceContract
{
    /**
     * @var SuperAdminContract
     */
    protected SuperAdminContract $superContract;
    /**
     * @var AirportContract
     */
    protected AirportContract $airportContract;
    /**
     * @var MetroContract
     */
    protected MetroContract $metroContract;
    /**
     * @var RailwayStationContract
     */
    protected RailwayStationContract $railwayContract;

    /**
     * SuperFranchiserService constructor.
     * @param  SuperAdminContract  $superFranchiserContract
     * @param  AirportContract  $airportContract
     * @param  MetroContract  $metroContract
     * @param  RailwayStationContract  $railwayContract
     */
    public function __construct(
        SuperAdminContract $superFranchiserContract,
        AirportContract $airportContract,
        MetroContract $metroContract,
        RailwayStationContract $railwayContract
    ) {
        $this->superContract = $superFranchiserContract;
        $this->airportContract = $airportContract;
        $this->metroContract = $metroContract;
        $this->railwayContract = $railwayContract;
    }

    /**
     * @param $name
     * @param $password
     * @return mixed|null
     */
    public function checkComparePassword($name, $password)
    {
        $admin = $this->superContract->findBy('name', $name, ['super_admin_id', 'password']);

        if (!$admin) {
            return null;
        }

        if (!Hash::check($password, $admin->password)) {
            return null;
        }

        return $admin;
    }

    /*=============================================================================================
                                            AIRPORT CRUD
    ==============================================================================================*/
    /**
     * @inheritDoc
     */
    public function airportsDataTable($page, $per_page, string $search = null, string $city = null)
    {
        return $this->airportContract
            ->when($city, fn(Builder $query) => $query->whereHas('city', fn($city) => $city->where('name', '=', $city)))
            ->when($search, fn(Builder $query) => $query
                ->where('name', 'LIKE', '%'.$search.'%')
                ->orWhereHas('city', fn($city) => $city->where('name', 'LIKE', '%'.$search.'%')))
            ->with(['city' => fn($query) => $query->select('*')])
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @inheritDoc
     */
    public function createAirport(array $airport): ?int
    {
        $create = $this->airportContract->create([
            'name' => $airport['name'],
            'city_id' => $airport['city'],
            'terminal' => $airport['terminal'],
            'address' => $airport['address'],
            'lat' => $airport['cord'][0],
            'lut' => $airport['cord'][1],
        ]);

        return $create->airport_id ?? null;
    }

    /**
     * @inheritDoc
     */
    public function updateAirport($airport_id, array $airport): bool
    {
        $updated = $this->airportContract
            ->where('airport_id', '=', $airport_id)
            ->updateSet([
                'name' => $airport['name'],
                'city_id' => $airport['city'],
                'terminal' => $airport['terminal'],
                'address' => $airport['address'],
                'lat' => $airport['cord'][0],
                'lut' => $airport['cord'][1]
            ]);

        if (!$updated) {
            return false;
        }

        return true;
    }

    /**
     * @param  int  $airport_id
     * @return bool
     */
    public function deleteAirport(int $airport_id): bool
    {
        if (!$this->airportContract->delete($airport_id)) {
            return false;
        }

        return true;
    }


    /*=============================================================================================
                                            METRO CRUD
    ==============================================================================================*/
    /**
     * @inheritDoc
     */
    public function metrosDataTable($page, $per_page, string $search = null, string $city = null)
    {
        return $this->metroContract
            ->when($city, fn(Builder $query) => $query->whereHas('city', fn($city) => $city->where('name', '=', $city)))
            ->when($search, fn(Builder $query) => $query
                ->where('name', 'LIKE', '%'.$search.'%')
                ->orWhereHas('city', fn($city) => $city->where('name', 'LIKE', '%'.$search.'%')))
            ->with(['city' => fn($query) => $query->select('*')])
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @inheritDoc
     */
    public function createMetro(array $metro): ?int
    {
        $create = $this->metroContract->create([
            'name' => $metro['name'],
            'city_id' => $metro['city'],
            'input' => $metro['input'],
            'address' => $metro['address'],
            'lat' => $metro['cord'][0],
            'lut' => $metro['cord'][1],
        ]);

        return $create->metro_id ?? null;
    }

    /**
     * @inheritDoc
     */
    public function updateMetro($metro_id, array $metro): bool
    {
        $updated = $this->metroContract
            ->where('metro_id', '=', $metro_id)
            ->updateSet([
                'name' => $metro['name'],
                'city_id' => $metro['city'],
                'input' => $metro['input'],
                'address' => $metro['address'],
                'lat' => $metro['cord'][0],
                'lut' => $metro['cord'][1]
            ]);

        if (!$updated) {
            return false;
        }

        return true;
    }

    /**
     * @param  int  $metro_id
     * @return bool
     */
    public function deleteMetro(int $metro_id): bool
    {
        if (!$this->metroContract->delete($metro_id)) {
            return false;
        }

        return true;
    }

    /*=============================================================================================
                                            RAILWAY CRUD
    ==============================================================================================*/
    /**
     * @inheritDoc
     */
    public function railwayDataTable($page, $per_page, string $search = null, string $city = null)
    {
        return $this->railwayContract
            ->when($city, fn(Builder $query) => $query->whereHas('city', fn($city) => $city->where('name', '=', $city)))
            ->when($search, fn(Builder $query) => $query
                ->where('name', 'LIKE', '%'.$search.'%')
                ->orWhereHas('city', fn($city) => $city->where('name', 'LIKE', '%'.$search.'%')))
            ->with(['city' => fn($query) => $query->select('*')])
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @inheritDoc
     */
    public function createRailway(array $railway): ?int
    {
        $create = $this->railwayContract->create([
            'name' => $railway['name'],
            'city_id' => $railway['city'],
            'input' => $railway['input'],
            'address' => $railway['address'],
            'lat' => $railway['cord'][0],
            'lut' => $railway['cord'][1],
        ]);

        return $create->railway_station_id ?? null;
    }

    /**
     * @inheritDoc
     */
    public function updateRailway($railway_id, array $railway): bool
    {
        $updated = $this->railwayContract
            ->where('railway_station_id', '=', $railway_id)
            ->updateSet([
                'name' => $railway['name'],
                'city_id' => $railway['city'],
                'input' => $railway['input'],
                'address' => $railway['address'],
                'lat' => $railway['cord'][0],
                'lut' => $railway['cord'][1]
            ]);

        if (!$updated) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteRailway(int $railway_id): bool
    {
        if (!$this->railwayContract->delete($railway_id)) {
            return false;
        }

        return true;
    }
}
