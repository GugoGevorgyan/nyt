<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AuthByDataRequest
 * @package Src\Http\Requests\ClientMobile
 */
class AuthByDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'exists:clients,email', 'email'],
            'password' => ['required', 'min:3', 'max:50']
        ];
    }
}
