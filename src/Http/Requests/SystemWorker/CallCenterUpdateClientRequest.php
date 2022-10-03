<?php

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

class CallCenterUpdateClientRequest extends FormRequest
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
        $id = $this->route('client_id');

        return [
            'phone' => ['required', 'unique:clients,phone,'.$id.',client_id', 'unique:company_phones,number'],
            'name' => ['nullable', 'max:100'],
            'surname' => ['nullable', 'max:100'],
            'patronymic' => ['nullable', 'max:100'],
            'email' => ['nullable', 'email', 'max:100']
        ];
    }
}
