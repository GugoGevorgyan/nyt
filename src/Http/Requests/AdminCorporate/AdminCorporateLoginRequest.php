<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminCorporate;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PersonalClientAdminLoginRequest
 * @property mixed email
 * @property mixed password
 * @package Src\Http\Requests
 */
class AdminCorporateLoginRequest extends FormRequest
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
    public function rules()
    {
        return [
            'email' => 'required|has_exists_personal_admin:be,'.$this->validationData()['password'],
            'password' => 'required'
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
            'email.required' => 'Email or Username is required',
            'password.required' => 'Password is required',
        ];
    }
}
