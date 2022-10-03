<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class EditSettingsRequest
 * @package Src\Http\Requests\ClientMobile
 */
class EditSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'show_driver_my_coordinates' => ['required', 'boolean'],
            'not_call' => ['required', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $this->request->replace(['show_driver_my_coordinates' => $this->show_cord, 'not_call' => $this->call]);
        return $this->request->all();
    }
}
