<?php
declare(strict_types=1);


namespace Src\Services\DriverContract;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface DriverContractServiceContract
 * @package Src\Services\DriverContract
 */
interface DriverContractServiceContract extends BaseContract
{
    /**
     * @param $request
     * @return bool|array
     */
    public function createContractFile($request);

    /**
     * @param $request
     * @return mixed
     */
    public function driverContractsPaginate($request);

    /**
     * @param $request
     * @return mixed
     */
    public function unsignedDriversPaginate($request): LengthAwarePaginator;

    /**
     * @param $request
     * @return object|null
     */
    public function createUnsignedContract($request): ?object;

    /**
     * @param $contract_id
     * @return mixed
     */
    public function signContract($contract_id);

    /**
     * @param $request
     * @return mixed
     */
    public function terminateContract($request);

    /**
     * @param  int  $contract_id
     * @return string
     */
    public function downloadContract(int $contract_id): ?string;

    /**
     * @param array $data
     * @return bool
     */
    public function updateDriverContractPrice(array $data): ?object;
}
