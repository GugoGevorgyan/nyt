<?php

declare(strict_types=1);


namespace Src\Services\Worker;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ServiceEntity\Contract\BaseContract;
use Src\Exceptions\Lexcept;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Interface WorkerServiceContract
 * @package Src\Services\SystemWorker
 */
interface WorkerServiceContract extends BaseContract
{
    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function workersPaginate($request): LengthAwarePaginator;

    /**
     * @param $request
     * @return mixed
     */
    public function createSystemWorker($request);

    /**
     * @param $system_worker_id
     * @return mixed
     */
    public function deleteWorker($system_worker_id);

    /**
     * @param $system_worker_id
     * @return mixed
     */
    public function getWorkerById($system_worker_id);

    /**
     * @return Collection
     */
    public function getFranchiseTutors(): Collection;

    /**
     * @param $request
     * @param $worker_id
     * @return mixed
     */
    public function updateWorker($request, $worker_id);

    /**
     * @param  int  $page
     * @param  int  $per_page
     * @param  array  $filters
     * @return LengthAwarePaginator
     */
    public function getFranchiseWaybills(int $page = 1, int $per_page = 15, array $filters = []): LengthAwarePaginator;

    /**
     * @param $waybill_id
     * @return Collection
     */
    public function getWaybillDetails($waybill_id): Collection;

    /**
     * @param $waybill_id
     * @return Collection
     */
    public function getWaybillImages($waybill_id): Collection;

    /**
     * @param $waybill_id
     * @return null|string
     */
    public function annulWaybill($waybill_id): ?string;

    /**
     * @param $waybill_id
     * @return mixed
     */
    public function printWaybill($waybill_id);

    /**
     * @param $roleName
     * @return Collection
     */
    public function getFranchiseWorkersByRoleName($roleName): Collection;

    /**
     * @param  SystemWorker  $worker
     * @return mixed
     */
    public function stopSession(SystemWorker $worker);

    /**
     * @param  SystemWorker  $worker
     * @param  string  $nickname
     * @param $password
     * @param $token
     * @return bool|null
     */
    public function startSession(SystemWorker $worker, string $nickname, $password, $token): ?bool;

    /**
     * @param $system_worker_ids
     * @return bool|null
     */
    public function deleteWorkers($system_worker_ids): ?bool;

    /**
     * @param  int  $driver_id
     * @param  int|null  $late_minute
     * @return bool|null
     * @throws Lexcept
     */
    public function lateOrder(int $driver_id, int $late_minute = null): ?bool;

    /**
     * @param  string  $waybill_number
     * @param  array  $data
     * @param  array  $questions
     * @param  array  $images
     * @return bool
     */
    public function report(string $waybill_number, array $data, array $questions, array $images): bool;

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function bookkeepingPaginate($request): LengthAwarePaginator;

    /**
     * @param $transaction_id
     * @return object|null
     */
    public function bookkeepingTransactionInfo($transaction_id): ?object;

    /**
     * @param $type
     * @param $side
     * @param $sum
     * @param $input
     * @return array
     */
    public function workerCreateTransaction($type, $side, $sum, $input, $comment): array;

    /**
     * @param $side
     * @param $date_start
     * @param $date_end
     * @param  bool  $print
     * @return mixed
     */
    public function createPrintTransaction($side, $date_start, $date_end): Xlsx;

    /**
     * @return array
     */
    public function getBookkeepingProps(): array;

    /**
     * @return mixed
     */
    public function getDriversOfParks($data);

    /**
     * @param  array|int|string  $franchise_ids
     * @return Collection
     */
    public function getCallCenterWorkers($franchise_ids): Collection;

    /**
     * @param $worker_id
     * @return object
     */
    public function getProfileWorker($worker_id): object;

    /**
     * @param $request
     * @return object|null
     */
    public function updateProfile($request): ?object;

    /**
     * @param $system_worker_id
     * @return mixed
     */
    public function getSubPhone($system_worker_id);

    /**
     * @param $system_worker_id
     * @return string
     */
    public function getOperatorSubPhone($system_worker_id): object|string;

    /**
     * @return Collection
     */
    public function getFranchiseOperators(): Collection;

    /**
     * @param $request
     * @return object|bool
     */
    public function operatorAttachOrder($request): bool|object;

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function franchiseOperatorsPaginate($request): LengthAwarePaginator;

    /**
     * @param  array  $data
     * @return mixed
     */
    public function bookkeepingCompanyOrders(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function bookkeepingCompanyOrdersReport(array $data);

    /**
     * @param  array  $data
     * @return mixed
     */
    public function bookkeepingCompanyOrdersDownload(array $data);

    /**
     * @param  int  $order_id
     * @param $distance
     * @param $duration
     * @param  bool  $cross
     * @param  null  $cost
     * @return null|object
     */
    public function recalculateOrder(int $order_id, $distance, $duration, bool $cross = null, $cost = null): ?object;

    /**
     * @param  array  $payload
     * @return LengthAwarePaginator
     */
    public function getClients(array $payload): LengthAwarePaginator;

    /**
     * @param $order_id
     * @param $worker_id
     * @return mixed
     */
    public function changeOrderToManuality($order_id, $worker_id);

    /**
     * @param $order_id
     * @param $driver_id
     * @return bool
     */
    public function orderDriverUnpin($order_id, $driver_id): bool;

    /**
     * @param  int  $order_id
     * @param  string  $time
     * @param  bool  $now
     * @param  int|null  $driver_id
     * @return mixed
     */
    public function changePreorderData(int $order_id, string $time, bool $now, int $driver_id = null): bool;

    /**
     * @param  int  $order_id
     * @param  int  $radius
     * @param  int  $type
     * @return Collection
     */
    public function getDriverForEditOrder(int $order_id, int $radius, int $type): Collection;

    /**
     * @param  int|null  $order_id
     * @return array
     */
    public function getDriverForEditFilters(int $order_id = null): array;

    /**
     * @param  int  $order_id
     * @param  array  $drivers
     * @return mixed
     */
    public function sendOrderToDriversList(int $order_id, array $drivers, int $radius, $filter_type): bool;

    public function bookkeepingCompanyOrdersReportDownload($data);
}
