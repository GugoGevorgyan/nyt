<?php

namespace Src\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed subNumber
 * @property mixed cellNumber
 */
class CallEndRequest extends FormRequest
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
            'cellNumber' => 'required',
            'subNumber' => 'required|exists:franchise_sub_phones,number'
        ];
    }
}
