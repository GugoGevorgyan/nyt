<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminSuper;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use JsonException;
use ReflectionException;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\AdminSuper\CreateRoleRequest;
use Src\Http\Requests\AdminSuper\DestroyMultipleRolesRequest;
use Src\Http\Requests\AdminSuper\GetRolesRequest;
use Src\Http\Requests\AdminSuper\UpdateRoleRequest;
use Src\Models\Role\Role;
use Src\Services\Module\ModuleServiceContract;
use Src\Services\Role\RoleServiceContract;

/**
 * Class RolesController
 * @package Src\Http\Controllers\AdminSuper
 */
class RolesController extends Controller
{
    /**
     * @var
     */
    protected $baseService;

    /**
     * @var
     */
    protected $moduleService;

    /**
     * RolesController constructor.
     * @param  RoleServiceContract  $baseService
     * @param  ModuleServiceContract  $moduleService
     */
    public function __construct(
        RoleServiceContract $baseService,
        ModuleServiceContract $moduleService
    ) {
        $this->baseService = $baseService;
        $this->moduleService = $moduleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws JsonException
     */
    public function index()
    {
        return view(
            'admin-super.roles',
            [
                'modules' => $this->moduleService->getModules(),
                'guards' => json_encode(array_keys(config('auth.guards')), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            ]
        );
    }

    /**
     * @param  GetRolesRequest  $request
     * @return ResponseFactory|Response
     * @throws JsonException
     */
    public function paginate(GetRolesRequest $request)
    {
        $roles = $this->baseService->getRolesPaginate($request->validated());
        return response(json_encode($roles, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK, 512));
    }

    /**
     * @param  CreateRoleRequest  $request
     * @return JsonResponse
     * @throws ReflectionException
     */
    public function store(CreateRoleRequest $request): JsonResponse
    {
        return $request->createRole();
    }

    /**
     * @param  UpdateRoleRequest  $request
     * @param  Role  $role
     * @return JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        return $this->baseService->updateRole($request->all(), $role);
    }

    /**
     * @param  Role  $role
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Role $role): JsonResponse
    {
        $deleted = $role->delete();
        $message = 'Module has '.($deleted ? 'been' : 'not been').' deleted';
        $status = $deleted ? 200 : 400;

        return response()->json(['deleted' => $deleted, 'message' => $message], $status);
    }

    /**
     * @param  DestroyMultipleRolesRequest  $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function destroyMultiple(DestroyMultipleRolesRequest $request): JsonResponse
    {
        return $request->destroyAll();
    }
}
