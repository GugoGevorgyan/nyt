<?php

declare(strict_types=1);

namespace Src\Http\Requests\CarReport;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Role;

/**
 * Class ReportCreateRequest
 * @package Src\Http\Requests\CarReport
 */
class ReportCreateRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): ?bool
    {
        return Auth::check() && (Auth::user()->is_admin || Auth::user()->hasRole(Role::MECHANIC_API) || Auth::user()->hasRole(Role::PARK_MANAGER_API));
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'waybill_number' => ['required', 'exists:waybills,number'],
            'question' => ['required', 'array'],
            'data' => ['required', 'array'],
            'images' => ['required', 'min:4'],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'waybill_number.required' => 'Waybill_number required',
            'waybill_number.exists' => 'Waybill_number not found',
        ];
    }
}
