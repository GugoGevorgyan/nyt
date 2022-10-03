<?php

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Permission;

class UpdatePermissionRequest extends FormRequest
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

    public function save(Permission $permission)
    {
        // dd($this->all());
        $permission->update($this->all('permission_id', 'role_id', 'name', 'alias', 'description', 'guard_name'));
        $permission->role()->associate($this->input('role_id'));
        $permission->save();

        return response()->json(
            [
                'permission' => $permission->fresh('role'),
                'message' => 'Permission has been successfully updated!'
            ],
            200
        );
    }
}
