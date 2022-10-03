<?php

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Src\Models\Role\Permission;

class DestroyMultiplePermissionsRequest extends FormRequest
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
            //
        ];
    }

    public function destroyAll()
    {
        abort_if(!$this->query('ids'), 404);

        $validator = Validator::make(
            $this->query(),
            [
                'ids' => 'required|array',
                'ids.*' => 'nullable|exists:permissions,permission_id'
            ]
        );

        if ($validator->fails()) {
            throw ValidationException::withMessages(['ids' => ['unresolved entity!']]);
        }

        $permissions = Permission::findMany($this->query('ids'));

        $permissions->each(
            function (Permission $permissions) {
                $permissions->delete();
            }
        );

        return response()->json(
            [
                'permissions' => $permissions,
                'success' => true,
                'message' => 'Regions has been successfully deleted!'
            ],
            200
        );
    }
}
