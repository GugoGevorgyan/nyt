<?php

declare(strict_types=1);

namespace Src\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Src\Core\Enums\ConstRedis;
use Src\Support\Rules\ExistsRedis;

/**
 * Class ClientRegisterRequest
 * @property mixed phone
 * @property mixed accepted_code
 * @package Src\Http\Requests
 */
class ClientRegisterRequest extends FormRequest
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
            'phone' => 'required',
            'accept_code' => ['required', new ExistsRedis(ConstRedis::accept_code('client', $this->phone), 'accept_code')]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'phone.required' => 'Phone field is required',
            'phone.sms_code' => 'Please accept code in your phone number sms',
            'phone.check_client_valid_key_code' => 'Please enter valid code',
        ];
    }
}
