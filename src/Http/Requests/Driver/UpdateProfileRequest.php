<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OrderReadyRequest
 * @property mixed driver_id
 * @property mixed phone
 * @property mixed graphic_id
 * @property mixed type_id
 * @property mixed email
 * @package Src\Http\Requests\Driver
 */
class UpdateProfileRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:drivers,email, '.$this->driver_id.',driver_id',
            'nickname' => ['required'],
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

        ];
    }
}
