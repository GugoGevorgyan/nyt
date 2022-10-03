<?php

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

class CallCenterCallEndRequest extends FormRequest
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
            'cellNumber' => 'required|numeric',
            'subPhone' => 'required|numeric|exists:franchise_sub_phones,number',
        ];
    }
}
