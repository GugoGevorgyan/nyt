<?php

declare(strict_types=1);

namespace Src\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class CreateAddressesRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'client_id' => 'required|exists:clients,client_id',
            'address' => 'required',
            'lat' => 'required',
            'lut' => 'required',
            'type' => '',
            'porch' => '',
            'driver_hint' => ''
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [

//            'car_class_id.required' => 'Please check car type',
//            'phone.required' => 'Please write client phone',
//            'address_from.required' => 'Please check the address from',
//            'order_time.required' => 'Please check the order time',
//            'payment_type_id.required' => 'Please check Payment Type',
//            'payment_type_id.exists' => 'This payment type dont found',
        ];
    }
}

