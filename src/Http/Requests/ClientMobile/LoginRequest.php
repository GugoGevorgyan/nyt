<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Foundation\Http\FormRequest;
use Src\Core\Enums\ConstRedis;
use Src\Support\Rules\ExistsRedis;

/**
 * Class LoginRequest
 * @package Src\Http\Requests\ClientMobile
 */
class LoginRequest extends FormRequest
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
            'phone' => ['required'],
            'accept_code' => ['required', new ExistsRedis(ConstRedis::accept_code('client', $this->phone), 'accept_code')],
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
            'phone.required' => trans('validation.custom.rules.This phone already has an account'),
            'phone.non_exists' => trans('validation.custom.rules.This phone already has an account'),
            'phone.digits_between' => trans('validation.custom.rules.This phone already has an account'),
        ];
    }
}
