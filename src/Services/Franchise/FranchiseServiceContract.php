<?php

declare(strict_types=1);


namespace Src\Services\Franchise;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface FranchiseServiceContract
 * @package Src\Services\Franchise
 */
interface FranchiseServiceContract extends BaseContract
{
    /**
     * @return string|null
     */
    public function getFranchisePhoneCode(): ?string;

    /**
     * @param $request
     * @param $franchise_id
     * @return bool
     */
    public function updateFranchise($request, $franchise_id): bool;

    /**
     * @param $id
     * @return mixed
     */
    public function deleteFranchise($id);

    /**
     * @param $request
     * @return mixed
     */
    public function storeFranchise($request): bool;

    /**
     * @param $request
     * @return mixed
     */
    public function adminPaginate($request): LengthAwarePaginator;

    /**
     * @return array
     */
    public function getFranchiseRegion(): array;

    /**
     * @param $franchise_id
     * @return array
     */
    public function getEditFranchise($franchise_id): array;

    /**
     * @return mixed
     */
    public function getFranchiseEntitiesIe();

    /**
     * @return Collection
     */
    public function getFranchiseEntities(): Collection;

    /**
     * @param $request
     * @return mixed
     */
    public function createFranchiseAdmin($request);

    /**
     * @param $request
     * @param $system_worker_id
     * @return mixed
     */
    public function updateFranchiseAdmin($request, $system_worker_id);

    /**
     * @param $system_worker_id
     * @return mixed
     */
    public function deleteFranchiseAdmin($system_worker_id);

    /**
     * @param $franchise_phone_id
     * @return mixed
     */
    public function deleteFranchisePhone($franchise_phone_id);

    /**
     * @param $franchise_sub_phone_id
     * @return mixed
     */
    public function deleteFranchiseSubPhone($franchise_sub_phone_id);

    /**
     * @return mixed
     */
    public function getFranchiseRoles(): Collection;

    /**
     * @param $franchise_id
     * @return Collection
     */
    public function getFranchiseModuleRoles($franchise_id): Collection;

    /**
     * @param $franchise_id
     * @return mixed
     */
    public function getFranchiseSubPhones($franchise_id);
}
