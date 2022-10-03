<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminCorporate;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ScheduledCorporateOrdersRequest
 * @package Src\Http\Requests\AdminCorporate
 */
class ScheduledCorporateOrdersRequest extends FormRequest
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
            '*.car_class_id' => 'required',
            '*.passenger_id' => 'required',
            '*.phone' => 'required',
            '*.address_from' => 'required',
            '*.order_time' => 'required',
            '*.minutes' => 'required|integer|min:15'
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
            '*.car_class_id.required' => 'Please check car type',
            '*.passenger_id.required' => 'Please check the client',
            '*.phone.required' => 'Please write client phone',
            '*.address_from.required' => 'Please check the address from',
            '*.order_time.required' => 'Please check the order time'
        ];
    }
}
