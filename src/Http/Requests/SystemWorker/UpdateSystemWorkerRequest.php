<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Role;

/**
 * Class EditSystemWorkerRequest
 * @package Src\Http\Requests
 */
class UpdateSystemWorkerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $id = $this->route('system_worker_id');
        $data = $this->request->all();
        $operator = false;
        $dispatcher = false;

        if (isset($data['role_permissions']) && \is_array($data['role_permissions'])) {
            $roles = array_keys($data['role_permissions']);
            $operator_roles = Role::whereIn('name', [Role::OPERATOR_API, Role::OPERATOR_WEB])
                ->get()
                ->pluck('role_id')
                ->toArray();

            $dispatcher_roles = Role::whereIn('name', [Role::DISPATCHER_API, Role::DISPATCHER_WEB])
                ->get()
                ->pluck('role_id')
                ->toArray();

            foreach ($operator_roles as $operator_role) {
                if (\in_array($operator_role, $roles, true)) {
                    $operator = true;
                }
            }
            foreach ($dispatcher_roles as $dispatcher_role) {
                if (\in_array($dispatcher_role, $roles, true)) {
                    $dispatcher = true;
                }
            }
        }

        return [
            'name' => 'required|min:3|max:99',
            'surname' => 'required|min:3|max:99',
            'patronymic' => 'required|min:3|max:99',
            'nickname' => 'required|unique:system_workers,nickname,'.$id.',system_worker_id|min:3|max:99',
            'email' => 'nullable|unique:system_workers,email,'.$id.',system_worker_id|min:3|max:99',
            'phone' => 'nullable|max:18|min:9',
            'description' => 'nullable|string|max:999',
            'photo_file' => 'nullable|mimes:jpeg,jpg,png|max:512',
            'role_permissions' => 'required|array',
            'role_permissions.*' => 'required|array',

            'operator_sub_phone_id' => $operator ? 'required|exists:franchise_sub_phones,franchise_sub_phone_id' : '',
            'dispatcher_sub_phone_id' => $dispatcher ? 'required|exists:franchise_sub_phones,franchise_sub_phone_id' : '',

            'password' => isset($data['change_password']) && $data['change_password'] ? ['required', 'min:6', 'max:100'] : '',
        ];
    }
}
