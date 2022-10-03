<?php

declare(strict_types=1);


namespace Src\Services\Park;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use ServiceEntity\BaseService;
use Src\Repositories\LegalEntity\LegalEntityContract;
use Src\Repositories\LegalEntityBank\LegalEntityBankContract;
use Src\Repositories\Park\ParkContract;

/**
 * Class ParkService
 * @package Src\Services\Park
 */
class ParkService extends BaseService implements ParkServiceContract
{
    /**
     * @var ParkContract
     */
    protected ParkContract $parkContract;
    /**
     * @var LegalEntityContract
     */
    protected LegalEntityContract $entityContract;
    /**
     * @var LegalEntityBankContract
     */
    protected LegalEntityBankContract $bankContract;

    /**
     * ParkService constructor.
     * @param  ParkContract  $parkContract
     * @param  LegalEntityContract  $entityContract
     * @param  LegalEntityBankContract  $bankContract
     */
    public function __construct(
        ParkContract $parkContract,
        LegalEntityContract $entityContract,
        LegalEntityBankContract $bankContract
    ) {
        $this->parkContract = $parkContract;
        $this->entityContract = $entityContract;
        $this->bankContract = $bankContract;
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function parkPaginate($request): LengthAwarePaginator
    {
        $per_page = isset($request['per_page']) && is_numeric($request['per_page']) ? $request['per_page'] : 25;
        $page = isset($request->page) && is_numeric($request->page) ? $request->page : 1;
        $search = isset($request->search) && null != $request->search ? $request->search : '';

        return $this->parkContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->when($search, fn($q) => $q->where(fn($q) => $q->where('parks.name', 'LIKE', '%'.$search.'%')
                ->orWhereHas('region', fn($q) => $q->where('regions.name', 'LIKE', '%'.$search.'%'))
                ->orWhereHas('city', fn($q) => $q->where('cities.name', 'LIKE', '%'.$search.'%'))
                ->orWhereHas('manager', fn($q) => $q->where('system_workers.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('system_workers.surname', 'LIKE', '%'.$search.'%')
                    ->orWhere('system_workers.patronymic', 'LIKE', '%'.$search.'%'))
                ->orWhere('parks.address', 'LIKE', '%'.$search.'%')))
            ->with([
                'city' => fn($query) => $query->select(['cities.city_id', 'cities.region_id', 'cities.name']),
                'region' => fn($query) => $query->select(['regions.region_id', 'regions.country_id', 'regions.name']),
                'entity' => fn($query) => $query->select(['legal_entity_id', 'type_id', 'legal_entities.country_id', 'city_id', 'region_id', 'name', 'address', 'phone', 'email']),
                'manager' => fn($query) => $query->select(['system_worker_id', 'franchise_id', 'name', 'surname', 'patronymic'])
            ])
            ->orderBy('park_id', 'DESC')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @param $fields
     * @return \Illuminate\Support\Collection
     */
    public function getParksFields($fields): \Illuminate\Support\Collection
    {
        return $this->parkContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->findAll($fields);
    }

    public function getParkDrivers($park_id)
    {
        $park = $this->parkContract
            ->where([['park_id', '=', $park_id], ['franchise_id', '=', FRANCHISE_ID]])
            ->findFirst();

        if (!$park) {
            return false;
        }

        return $park->load('drivers')->drivers ?? null;
    }

    /**
     * @param $request
     * @return object|null
     */
    public function createPark($request): ?object
    {
        $park = $this->parkContract->create($this->prepareParkData($request));

        if (!$park) {
            return null;
        }

        return $this->parkContract->find($park->{$park->getKeyName()});
    }

    /**
     * @param $request
     * @return array
     */
    public function prepareParkData($request): array
    {
        return [
            'franchise_id' => FRANCHISE_ID,
            'name' => $request['name'],
            'city_id' => $request['city_id'],
            'address' => $request['address'],
            'manager_id' => $request['manager_id'],
            'entity_id' => $request['entity_id'],
        ];
    }

    /**
     * @param $request
     * @param $park_id
     * @return bool|Collection
     */
    public function updatePark($request, $park_id)
    {
        $park = $this->parkContract->whereExistsExist('park_id', '=', $park_id);

        return $park ? $this->parkContract->where('park_id', '=', $park_id)->updateSet($this->prepareParkData($request)) : false;
    }

    /**
     * @param $park_id
     * @return false|mixed
     */
    public function deletePark($park_id)
    {
        $park = $this->parkContract->whereExistsExist('park_id', '=', $park_id);

        return $park ? $this->parkContract->delete($park_id) : false;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getFranchiseParks(): \Illuminate\Support\Collection
    {
        return $this->parkContract->findWhere(['franchise_id', '=', FRANCHISE_ID]);
    }
}
