<?php

declare(strict_types=1);

namespace Src\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Src\Core\Contracts\BasePolitics;
use Src\Repositories\Franchisee\FranchiseContract;

/**
 * Class FranchiseeCrudPolicy
 * @package Src\Policies
 */
class FranchiseeCrudPolicy extends BasePolitics
{
    use HandlesAuthorization;

    /**
     * @var FranchiseContract
     */
    protected $franchiseContract;

    /**
     * Create a new policy instance.
     *
     * @param  FranchiseContract  $franchiseContract
     */
    public function __construct(FranchiseContract $franchiseContract)
    {
        $this->franchiseContract = $franchiseContract;
    }

    public function update($user, $model)
    {
    }

    public function view($user, $model)
    {
    }

    public function create($user, $model)
    {
    }

    public function destroy($user, $model)
    {
    }

    public function viewAny($user, $model)
    {
        // TODO: Implement viewAny() method.
    }
}
