<?php
declare(strict_types=1);


namespace Src\Services\Module;


use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Class ModuleServiceContract
 * @package Src\Services\Module
 */
interface ModuleServiceContract extends BaseContract
{
    /**
     * @return Collection
     */
    public function getModules(): Collection;

    /**
     * @return mixed
     */
    public function getAllModules();

    /**
     * @param $system_worker_id
     * @return mixed
     */
    public function getWorkerModuleIds($system_worker_id);

    /**
     * @param $request
     * @return mixed
     */
    public function createModule($request);

    /**
     * @param $request
     * @param $module_id
     * @return mixed
     */
    public function updateModule($request, $module_id);

    /**
     * @param $request
     * @return mixed
     */
    public function destroyMultiple($request);
}
