<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Role;

/**
 * Class DriverContractTerminateRequest
 * @property mixed password
 * @package Src\Http\Requests\SystemWorker
 */
class DriverContractTerminateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check()
            && (user()->is_admin || Auth::user()->hasRole(Role::WORKER_PERSONAL_DEPARTMENT_WEB) || Auth::user()->hasRole(Role::ADMIN_FRANCHISE_WEB));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'contract' => ['required', 'exists:driver_contracts,driver_contract_id'],
            'password' => ['required']
        ];
    }
}
