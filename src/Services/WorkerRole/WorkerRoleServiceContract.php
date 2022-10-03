<?php
declare(strict_types=1);


namespace Src\Services\WorkerRole;


use ServiceEntity\Contract\BaseContract;

/**
 * Interface WorkerRoleServiceContract
 * @package Src\Services\WorkerRole
 */
interface WorkerRoleServiceContract extends BaseContract
{
    public function getRolesName($system_worker_id);

    public function getRolesIds($system_worker_id);

    public function getPermissionsName($system_worker_id);

    public function getPermissionsIds($system_worker_id);

    public function getRolesPermissionsId($system_worker_id);
}
