<?php

declare(strict_types=1);

namespace Src\Http\Requests\Client;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ClientEmailValidationRequest
 * @package Src\Http\Requests\ClientMessage
 */
class ClientEmailValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|exists:clients.name'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name field is required',
            'name.exixts' => 'ClientMessage Not Registered',
        ];
    }
}
