<?php

declare(strict_types=1);


namespace Src\Services\ClientCall;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface ClientCallServiceContract
 * @package Src\Services\ClientCall
 */
interface ClientCallServiceContract extends BaseContract
{
    /**
     * @param $request
     * @return mixed
     */
    public function dispatcherCallsPaginate($request): LengthAwarePaginator;

    /**
     * @param $request
     * @return bool
     */
    public function atcLogged($request): bool;

    /**
     * @param $request
     * @return mixed
     */
    public function callReceivingData($request);

    /**
     * @param $clientPhone
     * @param $franchisePhone
     * @return mixed
     */
    public function clientRecentlyCall($clientPhone, $franchisePhone);

    /**
     * @param $clientPhone
     * @return mixed
     */
    public function getCaller($clientPhone);

    /**
     * @param $client
     * @param $franchisePhone
     * @param $cellNumber
     * @return mixed
     */
    public function createCall($client, $franchisePhone, $cellNumber);

    /**
     * @param $number
     * @return mixed
     */
    public function franchisePhoneValues($number);

    /**
     * @param $cellNumber
     * @param $subPhone
     * @return mixed
     */
    public function connectWorker($cellNumber, $subPhone): ?array;

    /**
     * @param $cellNumber
     * @param $subPhone
     * @return object|null
     */
    public function callStart($cellNumber, $subPhone): ?object;

    /**
     * @param $call_id
     * @return mixed
     */
    public function callAnswered($call_id);

    /**
     * @param $cellNumber
     * @param $subPhone
     * @return mixed
     */
    public function callEnd($cellNumber, $subPhone);

    /**
     * @param $subPhone
     * @param $days
     * @return mixed
     */
    public function getWorkerCalls($subPhone, $days): Collection;
}
