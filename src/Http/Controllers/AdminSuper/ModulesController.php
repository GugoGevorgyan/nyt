<?php
declare(strict_types=1);

namespace Src\Http\Controllers\AdminSuper;

use Src\Http\Controllers\Controller;
use Src\Http\Requests\AdminSuper\CreateModuleRequest;
use Src\Http\Requests\AdminSuper\DestroyMultipleModulesRequest;
use Src\Http\Requests\AdminSuper\UpdateModuleRequest;
use Src\Models\Franchise\Module;
use Src\Models\Role\Role;
use Src\Services\Module\ModuleServiceContract;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class ModulesController
 * @package Src\Http\Controllers\AdminSuper
 */
class ModulesController extends Controller
{
    /**
     * @var
     */
    protected $moduleService;

    /**
     * ModulesController constructor.
     * @param  ModuleServiceContract  $moduleService
     */
    public function __construct(ModuleServiceContract $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin-super.modules', [
            'roles' => Role::doesntHave('module')->get(),
            'modules' => Module::with('roles')->get()
        ]);
    }

    /**
     * @param  CreateModuleRequest  $request
     * @return JsonResponse
     */
    public function store(CreateModuleRequest $request)
    {
        $store = $this->moduleService->createModule($request);
        if (!$store) {
            return response('Something went wrong', 400);
        }
        return $store;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param  UpdateModuleRequest  $request
     * @param $module_id
     * @return ResponseFactory|Response
     */
    public function update(UpdateModuleRequest $request, $module_id)
    {

        $update = $this->moduleService->updateModule($request, $module_id);
        if (!$update) {
            return response('Something went wrong', 400);
        }
        return $update;
    }

    /**
     * @param  DestroyMultipleModulesRequest  $request
     * @return ResponseFactory|Response
     */
    public function destroyMultiple(DestroyMultipleModulesRequest $request)
    {
        $deletes = $this->moduleService->destroyMultiple($request);

        if (!$deletes) {
            return response('Something went wrong', 400);
        }

        return response('Modules Deleted', 200);
    }

    /**
     * Dissociating WorkerRole from Module
     *
     * @param  Role  $role
     * @return JsonResponse
     */
    public function dissociateRole(Role $role)
    {
        $role->module()->dissociate();
        $role->save();

        return response()->json([
            'role' => $role->fresh(),
            'message' => 'WorkerRole has been successfully dissociated from module!'
        ], 200);
    }

    /**
     * Associating WorkerRole with Module
     *
     * @param  Module  $module
     * @param  Role  $role
     * @return JsonResponse
     */
    public function associateRole(Module $module, Role $role)
    {
        $role->module()->associate($module);
        $role->save();

        return response()->json([
            'role' => $role->fresh(),
            'message' => 'WorkerRole has been successfully associated with module!'
        ], 200);
    }
}
