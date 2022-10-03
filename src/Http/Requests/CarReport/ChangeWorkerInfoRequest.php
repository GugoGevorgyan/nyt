<?php

namespace Src\Http\Requests\CarReport;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Role;

class ChangeWorkerInfoRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()->hasRole(Role::MECHANIC_API) || Auth::user()->hasRole(Role::PARK_MANAGER_API)) {
            return true;
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:150|min:3',
            'surname' => 'required|max:150|min:3',
            'nickname' => 'required|unique:system_workers,nickname,'.auth()->id().',system_worker_id',
            'email' => 'required|unique:system_workers,email,'.auth()->id().',system_worker_id',
            'password' => 'max:64|min:6',
            'phone' => 'required|unique:system_workers,phone,'.auth()->id().',system_worker_id',
            'photo' => 'mimes:jpeg,jpg,png|max:512',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [];
    }
}
