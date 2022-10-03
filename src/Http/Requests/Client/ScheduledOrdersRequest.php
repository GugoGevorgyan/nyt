<?php

namespace Src\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class ScheduledOrdersRequest extends FormRequest
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
            '*.car_class_id' => 'required',
            '*.phone' => 'required',
            '*.address_from' => 'required',
            '*.order_time' => 'required',
            '*.minutes' => 'required|integer|min:15',
            '*.order_type_id' => 'required|exists:order_types,order_type_id',
            '*.payment_type_id' => 'required|exists:payment_types,payment_type_id'
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
            '*.phone.required' => 'Please write client phone',
            '*.address_from.required' => 'Please check the address from',
            '*.order_time.required' => 'Please check the order time',
            '*.order_type_id.required' => 'Please check Order Type',
            '*.order_type_id.exists' => 'This order type dont found',
            '*.payment_type_id.required' => 'Please check Payment Type',
            '*.payment_type_id.exists' => 'This payment type dont found',
        ];
    }
}
