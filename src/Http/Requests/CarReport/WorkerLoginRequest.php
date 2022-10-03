<?php

declare(strict_types=1);

namespace Src\Http\Requests\CarReport;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class WorkerLoginRequest
 * @package Src\Http\Requests\CarReport
 */
class WorkerLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'exists:system_workers,nickname'],
            'password' => ['required'],
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
            'username.required' => 'Login required',
            'username.exists' => 'Invalid data',
            'password.required' => 'Invalid data',
        ];
    }
}
