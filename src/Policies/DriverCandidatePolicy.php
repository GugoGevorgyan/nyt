<?php

declare(strict_types=1);

namespace Src\Policies;

use Src\Core\Contracts\BasePolitics;
use Src\Models\Driver\DriverCandidate;
use Src\Models\SystemUsers\SystemWorker;

/**
 * Class DriverCandidatePolicy
 * @package Src\Policies
 */
class DriverCandidatePolicy extends BasePolitics
{
    /**
     * Determine whether the user can view any driver candidates.
     *
     * @param  SystemWorker  $user
     * @return mixed
     */
    public function viewAny($user, $model)
    {
        //
    }

    /**
     * Determine whether the user can view the driver candidate.
     *
     * @param  SystemWorker  $user
     * @param  DriverCandidate  $driverCandidate
     * @return mixed
     */
    public function view($user, $model)
    {
        return $user->role == 'fewfgewfew';
        //
    }

    /**
     * Determine whether the user can CreateComponents driver candidates.
     *
     * @param  SystemWorker  $user
     * @return mixed
     */
    public function create($user, $model)
    {
        //
    }

    /**
     * Determine whether the user can update the driver candidate.
     *
     * @param  SystemWorker  $user
     * @param  DriverCandidate  $driverCandidate
     * @return mixed
     */
    public function update($user, $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the driver candidate.
     *
     * @param  SystemWorker  $user
     * @param  DriverCandidate  $driverCandidate
     * @return mixed
     */
    public function delete($user, $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the driver candidate.
     *
     * @param  SystemWorker  $user
     * @param $model
     * @return mixed
     */
    public function restore($user, $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the driver candidate.
     *
     * @param  SystemWorker  $user
     * @param  DriverCandidate  $driverCandidate
     * @return mixed
     */
    public function forceDelete($user, $model)
    {
        //
    }

    /**
     * @return mixed
     */
    public function destroy($user, $model)
    {
        // TODO: Implement destroy() method.
    }
}
