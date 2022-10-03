<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminCorporate;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CorporateClientUpdateRequest
 * @package Src\Http\Requests\AdminCorporate
 */
class CorporateClientUpdateRequest extends FormRequest
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
            // 'addresses.*.value' => 'exists_address:'.$this->route('id'),
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
            // 'addresses.*.value.exists_address' => 'This address for this user are exist ',
        ];
    }
}
