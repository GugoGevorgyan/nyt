<?php

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use ReflectionException;
use Src\Models\Role\Permission;

class CreatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_id' => 'required|integer|exists:roles,role_id',
            'name' => 'required|string|max:255',
            'alias' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ];
    }

    /**
     * Create Permission resource in store
     *
     * @return JsonResponse
     * @throws ReflectionException
     */
    public function createPermission()
    {
        /** @var Permission $permission */
        $permission = Permission::create($this->all('role_id', 'name', 'alias', 'description', 'guard_name'));
        $permission->role()->associate($this->input('role_id'));
        $permission->save();

        return response()->json(
            [
                'permission' => $permission->fresh('role'),
                'message' => 'Permission has been successfully created'
            ],
            200
        );
    }
}
