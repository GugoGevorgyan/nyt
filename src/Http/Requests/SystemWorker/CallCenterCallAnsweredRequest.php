<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CallCenterCallAnsweredRequest
 * @package Src\Http\Requests\SystemWorker
 */
class CallCenterCallAnsweredRequest extends FormRequest
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
            'call_id' => 'required|exists:client_calls,client_call_id'
        ];
    }
}
