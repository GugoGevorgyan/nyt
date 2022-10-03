<?php

declare(strict_types=1);


namespace Src\Services\Company;


use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface CompanyServiceContract
 * @package Src\Services\Company
 */
interface CompanyServiceContract extends BaseContract
{
    /**
     * @return null|array
     */
    public function getData(): ?array;

    /**
     * @return mixed
     */
    public function getCompany();

    /**
     * @param  array  $request
     * @param  int  $id
     * @return mixed
     */
    public function updateCompanyFranchise(array $request, int $id): ?bool;

    /**
     * @param $request
     * @return mixed
     */
    public function getOrdersPaginate($request): Collection;

    /**
     * @param $request
     * @return mixed
     */
    public function franchiseCompaniesPaginate($request);

    /**
     * @param $request
     * @return bool|null
     */
    public function createCompanyFranchise($request): ?bool;

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function adminUpdateCompany($id, $data);

    /**
     * @param $company_id
     * @return mixed
     */
    public function deleteFranchiseCompany($company_id);

    /**
     * @param $phone
     * @return mixed
     */
    public function getFranchiseCompanyByPhone($phone);

    /**
     * @param  array  $coordinate
     * @param  int  $company_id
     * @return bool|null
     */
    public function hasCompanyIsCoordinate(array $coordinate, int $company_id): ?bool;

    /**
     * @param $phone
     * @param  array  $values
     * @return mixed
     */
    public function getCompanyByPhone($phone, array $values = []);

    /**
     * @param $company_id
     * @return string|null
     */
    public function getPhoneMask($company_id): ?string;

    /**
     * @param $company_id
     * @param $tariff_ids
     * @return bool|array
     */
    public function companyAttachTariff($company_id, $tariff_ids): bool|array;

    /**
     * @param $client_phone
     * @param $company_id
     * @return void
     */
    public function closeOrderDialog($client_phone, $company_id): void;

    /**
     * @param $admin_id
     * @return object|null
     */
    public function getCompanyCities($admin_id): ?object;

    /**
     * @return Collection
     */
    public function getCities(): Collection;

    /**
     * @param  int  $city
     * @return Collection|null
     */
    public function getAirports(int $city): ?Collection;

    /**
     * @param  int  $city
     * @return Collection|null
     */
    public function getStations(int $city): ?Collection;

    /**
     * @param  int  $city_id
     * @return Collection|null
     */
    public function getMetros(int $city_id): ?Collection;

    /**
     * @param $request
     * @param  bool  $print
     * @return Collection
     */
    public function generateOrderExcel($request, bool $print = true): Collection;

    /**
     * @param $request
     * @param $order_ids
     * @return array
     */
    public function printExcelData($request): array;

    /**
     * @param $code
     * @param $franchise_id
     * @return object
     */
    public function findCompaniesByCode($code, $franchise_id): object;

    /**
     * @param $order_id
     * @param $client_id
     * @return mixed
     */
    public function cancelOrder($order_id, $client_id): bool;

    /**
     * @param $franchise_id
     * @return mixed
     */
    public function getCurrentPhoneMask($franchise_id): mixed;
}
