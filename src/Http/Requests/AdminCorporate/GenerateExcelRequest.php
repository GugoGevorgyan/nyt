<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminCorporate;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed id
 * @property mixed ids
 */
class GenerateExcelRequest extends FormRequest
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
