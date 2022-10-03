<?php

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
use Src\Http\Requests\AdminSuper\CreatePermissionRequest;
use Src\Http\Requests\AdminSuper\DestroyMultiplePermissionsRequest;
use Src\Http\Requests\AdminSuper\GetPermissionsRequest;
use Src\Http\Requests\AdminSuper\UpdatePermissionRequest;
use Src\Models\Role\Permission;
use Src\Services\Permission\PermissionServiceContract;
use Src\Services\Role\RoleServiceContract;

/**
 * Class PermissionsController
 * @package Src\Http\Controllers\AdminSuper
 */
class PermissionsController extends Controller
{
    /**
     * @var PermissionServiceContract
     */
    protected PermissionServiceContract $baseService;

    /**
     * @var RoleServiceContract
     */
    protected RoleServiceContract $roleService;


    /**
     * PermissionsController constructor.
     * @param  PermissionServiceContract  $baseService
     * @param  RoleServiceContract  $roleService
     */
    public function __construct(
        PermissionServiceContract $baseService,
        RoleServiceContract $roleService
    ) {
        $this->roleService = $roleService;
        $this->baseService = $baseService;
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
            'admin-super.permissions',
            [
                'roles' => $this->roleService->getRoles(),
                'guards' => json_encode(array_keys(config('auth.guards')), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            ]
        );
    }

    /**
     * @param GetPermissionsRequest $request
     * @return Application|ResponseFactory|Response
     * @throws JsonException
     */
    public function paginate(GetPermissionsRequest $request)
    {
        $permissions = $this->baseService->getPermissionsPaginate($request->validated());

        return response(json_encode($permissions, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE, 512));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePermissionRequest  $request
     * @return JsonResponse
     * @throws ReflectionException
     */
    public function store(CreatePermissionRequest $request): JsonResponse
    {
        return $request->createPermission();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePermissionRequest  $request
     * @param  Permission  $permission
     * @return JsonResponse
     */
    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        return $request->save($permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Permission  $permission
     * @return void
     * @throws Exception
     */
    public function destroy(Permission $permission)
    {
        $deleted = $permission->delete();
        $message = 'Module has '.($deleted ? 'been' : 'not been').' deleted';
        $status = $deleted ? 200 : 400;

        return response()->json(['deleted' => $deleted, 'message' => $message], $status);
    }

    /**
     * @param  DestroyMultiplePermissionsRequest  $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function destroyMultiple(DestroyMultiplePermissionsRequest $request): JsonResponse
    {
        return $request->destroyAll();
    }
}
