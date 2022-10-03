<?php

declare(strict_types=1);

namespace Src\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ClientPhoneNumberRequest
 * @package Src\Http\Requests
 */
class ClientPhoneNumberRequest extends FormRequest
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
            'phone' => "required|numeric"
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
            'phone.required' => 'Phone Number is required',
            'phone.numeric' => 'Phone Number is not numeric',
            'phone.exists' => 'Please enter other phone'
        ];
    }
}
