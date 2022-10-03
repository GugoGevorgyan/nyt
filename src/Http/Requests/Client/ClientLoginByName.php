<?php

declare(strict_types=1);

namespace Src\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ClientLoginByName
 * @property mixed email
 * @property mixed password
 * @package Src\Http\Requests\ClientMessage
 */
class ClientLoginByName extends FormRequest
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
            'email' => ['required', 'exists:clients,email', 'max:120'],
            'password' => ['required'],
//            'captcha_token' => ['required', 'captcha']
        ];
    }

    /**
     * @return array
     */
//    public function validationData()
//    {
//        $this->request->replace(['email' => $this->email, 'password' => $this->password]);
//        return $this->all();
//    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Name is required',
            'email.exists' => 'Fields is invalid',
            'password.required' => 'Password is required',
//            'captcha_token.required' => 'Error Fatale Bot'
        ];
    }
}
