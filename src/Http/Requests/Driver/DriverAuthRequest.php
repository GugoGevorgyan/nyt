<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DriverAuthRequest
 * @package Src\Http\Requests
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
            'username' => ['required', 'exists:drivers,nickname'],
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
            'username.required' => 'Driver username required',
            'username.exists' => 'Username or password invalid'
        ];
    }
}
