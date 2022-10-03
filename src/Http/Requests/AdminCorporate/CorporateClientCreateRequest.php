<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminCorporate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class CorporateClientCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'name' => ['required'],
            'phone' => ['required'],
            'limit' => ['required'],
            'allow_weekends' => ['required'],
            'allow_order' => ['required'],
            'car_classes' => ['required']
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
            'name.required' => 'Name is required',
            'phone.required' => 'Phone is required',
            'limit.required' => 'Limit is required',
            'allow_weekends.required' => 'Allow Weekends is required',
            'allow_order.required' => 'Allow Order is required',
        ];
    }
}
