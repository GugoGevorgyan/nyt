<?php

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Src\Models\Role\Role;

class DestroyMultipleRolesRequest extends FormRequest
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

    /**
     * @return JsonResponse
     */
    public function destroyAll()
    {
        abort_if(!$this->query('ids'), 422, 'Wrong data provided');

        $validator = Validator::make(
            $this->query(),
            [
                'ids' => 'required|array',
                'ids.*' => 'nullable|exists:roles,role_id'
            ]
        );

        if ($validator->fails()) {
            throw ValidationException::withMessages(['ids' => ['unresolved entity!']]);
        }

        /** @var Collection $roles */
        $roles = Role::findMany($this->query('ids'));

        $roles->each(
            function (Role $role) {
                $role->delete();
            }
        );

        return response()->json(
            [
                'success' => true,
                'message' => 'Roles has been successfully deleted!'
            ],
            200
        );
    }
}
