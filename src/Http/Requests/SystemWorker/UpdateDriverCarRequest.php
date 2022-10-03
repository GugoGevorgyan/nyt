<?php

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Role;

class UpdateDriverCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasRole(Role::WORKER_PERSONAL_DEPARTMENT_WEB);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'driver_id' => 'required|exists:drivers,driver_id',
        ];
    }
}
