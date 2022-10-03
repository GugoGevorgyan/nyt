<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Foundation\Http\FormRequest;
use Src\Support\Rules\Client\IsOnline;

/**
 * Class RegisterRequest
 * @package Src\Http\Requests\ClientMobile
 */
class RegisterRequest extends FormRequest
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
            'phone' => ['required', new IsOnline()]
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
            'phone.required' => trans('validation.custom.rules.This phone already has an account'),
        ];
    }
}
