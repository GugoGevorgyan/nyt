<?php

declare(strict_types=1);


namespace Src\Services\Complaint;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface ComplaintServiceContract
 * @package Src\Services\Complaint
 */
interface ComplaintServiceContract extends BaseContract
{
    /**
     * @param $request
     * @param  false  $worker_id
     * @return mixed
     */
    public function paginate($request, $worker_id = null): LengthAwarePaginator;

    /**
     * @return mixed
     */
    public function getStatuses(): Collection;

    /**
     * @param $request
     * @param $complaint_id
     * @return mixed
     */
    public function complaintComments($request, $complaint_id): LengthAwarePaginator;

    /**
     * @param $request
     * @return object|null
     */
    public function commentCreate($request): ?object;

    /**
     * @param $request
     * @return mixed
     */
    public function updateStatus($request);

    /**
     * @param $request
     * @return mixed
     */
    public function complaintCreate($request): bool;
}
