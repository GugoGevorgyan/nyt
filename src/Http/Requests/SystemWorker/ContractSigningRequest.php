<?php

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Role;

class ContractSigningRequest extends FormRequest
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
            'expiration_day' => 'required|date|after:today',
            'work_start_day' => 'required|date|after_or_equal:today|before_or_equal:expiration_day'
        ];
    }
}
