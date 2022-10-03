<?php

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Role;

class DriverCandidateCheckLicenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return
            ((
                Auth::check() &&
                    Auth::user()->is_admin
            ) ||
                Auth::user()->hasRole(Role::HEAD_PERSONAL_DEPARTMENT_WEB)) ||
            Auth::user()->hasRole(Role::WORKER_PERSONAL_DEPARTMENT_WEB) ||
            Auth::user()->hasRole(Role::TUTOR_WEB);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'license' => 'required|min:6|max:16'
        ];
    }
}
