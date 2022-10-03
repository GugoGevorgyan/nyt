<?php

declare(strict_types=1);

namespace Src\Http\Requests\Terminal;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DriverAuthRequest
 * @package Src\Http\Requests\Terminal
 */
class DriverAuthRequest extends FormRequest
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
            'login' => ['required', 'exists:drivers,nickname'],
            'password' => ['required']
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
            'login.required' => trans('validation.custom.rules.Phone number required'),
            'login.exists' => trans('validation.custom.rules.Wrong number')
        ];
    }
}
