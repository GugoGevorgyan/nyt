<?php

declare(strict_types=1);

namespace Src\Core\Contracts;

use Illuminate\Auth\Access\HandlesAuthorization;

/**
 *
 */
abstract class BasePolitics
{
    use HandlesAuthorization;

    /**
     * @param $user
     * @param $model
     * @return mixed
     */
    abstract public function viewAny($user, $model);

    /**
     * @param $user
     * @param $model
     * @return mixed
     */
    abstract public function view($user, $model);

    /**
     * @param $user
     * @param $model
     * @return mixed
     */
    abstract public function create($user, $model);

    /**
     * @param $user
     * @param $model
     * @return mixed
     */
    abstract public function update($user, $model);

    /**
     * @param $user
     * @param $model
     * @return mixed
     */
    abstract public function destroy($user, $model);
}
