<?php

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

class CallCenterCreateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; /*Auth::user()->hasRole(Role::CALL_CENTER_OPERATOR_WEB); @todo*/
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => ['required', 'unique:clients,phone', 'unique:company_phones,number'],
            'name' => ['nullable', 'max:100'],
            'surname' => ['nullable', 'max:100'],
            'patronymic' => ['nullable', 'max:100'],
            'email' => ['nullable', 'email', 'max:100']
        ];
    }
}
