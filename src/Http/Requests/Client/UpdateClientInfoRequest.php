<?php

namespace Src\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * @return array
     */
    public function rules()
    {
        $except = $this->route('id');
        return [
            'name' => 'required',
            'phone' => 'required|unique:clients,phone,'.$except.',client_id',
            'email' => 'unique:clients,email,'.$except.',client_id',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please write name',
            'phone.required' => 'Please write  phone',
        ];
    }
}
