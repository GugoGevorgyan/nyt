<?php

declare(strict_types=1);


namespace Src\Services\DriverCandidate;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface DriverCandidateServiceContract
 * @package Src\Services\DriverCandidate
 */
interface DriverCandidateServiceContract extends BaseContract
{
    /**
     * @param $offset
     * @param $limit
     * @return Collection|null
     */
    public function getCandidatesForCreateTutor($offset, $limit): ?Collection;

    /**
     * @param $candidate_data
     * @param $info_data
     * @return int|null
     */
    public function createCandidate($candidate_data, $info_data): ?int;

    /**
     * @param $candidate_id
     * @return mixed
     */
    public function deleteCandidate($candidate_id);

    /**
     * @param $candidate_ids
     * @return mixed
     */
    public function deleteManyCandidates($candidate_ids);

    /**
     * @param $request
     * @return object|null
     */
    public function checkLicense($request):object|null;

    /**
     * @param $candidate_id
     * @param $candidate
     * @param $info
     * @return bool
     */
    public function updateCandidate($candidate_id, $candidate, $info): bool;

    /**
     * @param $candidate_id
     * @return mixed
     */
    public function getEditedCandidate($candidate_id);

    /**
     * @param $request
     * @return mixed
     */
    public function candidatesPaginate($request): LengthAwarePaginator;

    /**
     * @param  int  $info_id
     * @return string
     */
    public function getPassportScan(int $info_id): string;

    /**
     * @return mixed
     */
    public function getLearnStatuses();

}
