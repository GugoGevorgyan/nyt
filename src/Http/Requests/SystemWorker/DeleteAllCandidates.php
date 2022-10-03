<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Role;

/**
 * Class DeleteAllCandidates
 * @property mixed confirm
 * @property mixed ids
 * @package Src\Http\Requests
 */
class DeleteAllCandidates extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
    public function rules(): array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'exists:driver_candidates,driver_candidate_id',
            'confirm' => 'required|exists_password:system_workers.password'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return ['confirm' => 'Password invalid'];
    }
}
