<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SuperFranchiseLoginRequest
 * @package Src\Http\Requests
 */
class AdminSuperLoginRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|exists:super_admin,name',
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
            'name.required' => 'Name is required',
            'name.exists' => 'Name not exists',
            'passwrod.required' => 'Passord required'
        ];
    }
}
