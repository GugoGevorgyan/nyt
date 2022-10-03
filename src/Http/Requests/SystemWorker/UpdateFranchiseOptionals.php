<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Role;

/**
 * Class UpdateFranchiseOptionals
 * @package Src\Http\Requests\SystemWorker
 */
class UpdateFranchiseOptionals extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->is_admin || Auth::user()->hasRole(Role::WORKER_PERSONAL_DEPARTMENT_WEB));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'driver_type_id' => ['required', 'exists:driver_types,driver_type_id'],
            'options' => ['sometimes', 'array'],
            'options_value' => ['nullable', 'numeric', 'max:100'],
        ];
    }
}
