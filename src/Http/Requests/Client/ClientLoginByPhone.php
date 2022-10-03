<?php

declare(strict_types=1);

namespace Src\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Src\Core\Enums\ConstRedis;
use Src\Support\Rules\ExistsRedis;

/**
 * Class ClientLoginByPhone
 * @property mixed phone
 * @package Src\Http\Requests
 */
class ClientLoginByPhone extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone' => ['required'],
            'accept_code' => ['required', new ExistsRedis(ConstRedis::accept_code('client', $this->phone), 'accept_code')]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Phone is required',
            'accept_code.required' => 'Accept Code is required',
        ];
    }

}
