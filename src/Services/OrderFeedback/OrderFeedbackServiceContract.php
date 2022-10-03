<?php
declare(strict_types=1);


namespace Src\Services\OrderFeedback;


use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface OrderFeedbackServiceContract
 * @package Src\Services\OrderFeedback
 */
interface OrderFeedbackServiceContract extends BaseContract
{
    /**
     * @param $request
     * @return mixed
     */
    public function complaintFranchisePaginate($request);

    /**
     * @return mixed
     */
    public function getOrderStatuses();

    /**
     * @return mixed
     */
    public function getTypes();

    /**
     * @return mixed
     */
    public function getWriters();

    /**
     * @param  string  $owner
     * @param  bool  $completed
     * @return Collection
     */
    public function getFeedbacks(string $owner, bool $completed = true): Collection;
}
