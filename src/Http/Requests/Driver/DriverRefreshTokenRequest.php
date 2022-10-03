<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DriverAuthRequest
 * @package Src\Http\Requests
 */
class DriverRefreshTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.required' => 'Driver username required',
            'username.exists' => 'Username or password invalid'
        ];
    }
}
