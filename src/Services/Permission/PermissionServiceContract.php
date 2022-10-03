<?php
declare(strict_types=1);


namespace Src\Services\Permission;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface PermissionServiceContract
 * @package Src\Services\Permission
 */
interface PermissionServiceContract extends BaseContract
{
    /**
     * @return mixed
     */
    public function getFranchisePermissions();

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getPermissionsPaginate($request): LengthAwarePaginator;
}
