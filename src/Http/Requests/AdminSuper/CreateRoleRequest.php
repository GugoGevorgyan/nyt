<?php

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use ReflectionException;
use Src\Models\Role\Role;

class CreateRoleRequest extends FormRequest
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
            'module_id' => 'required|integer|exists:modules,module_id',
            'name' => 'required|string|max:255',
            'alias' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ];
    }

    /**
     * @return JsonResponse
     * @throws ReflectionException
     */
    public function createRole()
    {
        /** @var Role $role */
        $role = Role::create($this->all('name', 'module_id', 'alias', 'guard_name', 'description'));
        $role->module()->associate($this->input('module_id'));
        $role->save();

        return response()->json(
            [
                'role' => $role->fresh('module'),
                'message' => 'Rol has been successfully created'
            ],
            200
        );
    }
}
