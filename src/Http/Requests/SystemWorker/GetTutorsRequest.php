<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetTutorsRequest
 * @package Src\Http\Requests\SystemWorker
 */
class GetTutorsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        return (
                (
                    Auth::check() &&
                    Auth::user()->is_admin
                ) ||
                Auth::user()->hasRole(Role::HEAD_PERSONAL_DEPARTMENT_WEB)
            ) ||
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
            'page' => 'required',
            'per-page' => 'required',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'page.required' => 'data invalid',
            'per-page.required' => 'data invalid',
        ];
    }
}
