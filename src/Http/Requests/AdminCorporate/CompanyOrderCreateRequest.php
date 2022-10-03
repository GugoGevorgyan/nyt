<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminCorporate;

use Auth;
use Src\Core\Enums\ConstGuards;
use Src\Core\Enums\ConstRentTimes;
use Src\Http\Requests\BaseRequest;

/**
 * Class CompanyOrderCreateRequest
 * @package Src\Http\Requests\AdminCorporate
 */
class CompanyOrderCreateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::ADMIN_CORPORATE_WEB()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rent_times = ConstRentTimes::getAll();

        return [
            'order.phone.client' => ['required'],
            'order.phone.passenger' => ['nullable'],
            'order.status' => ['required'],
            'order.client_id' => ['required'],
            'order.client' => ['nullable'],
            'order.company_id' => ['required'],
            'order.message' => ['nullable'],

            'order.route' => [
                'from' => ['required'],
                'to' => ['nullable'],
                'from_address' => 'required',
                'to_address' => ['nullable'],
            ],

            'order.time' => ['required', 'array'],
            'order.time.create_time' => ['required', 'date', 'date_format:Y-m-d H:i'],
            'order.time.time' => ['required', 'required', 'date', 'date_format:Y-m-d H:i', 'after_or_equal:now'],
            'order.time.zone' => ['required', 'timezone', 'string'],

            'order.payment.type' => ['required','exists:payment_types,payment_type_id'],
            'order.payment.company' => [
                'required_if:payment_type,2exists:payment_types,payment_type_id',
                'company_has_tariff_region:'.$this->validationData()['order']['route']['from'][0].','.$this->validationData()['order']['route']['from'][1]
            ],

            'order.car.options' => ['nullable'],
            'order.car.class' => ['required'],
            'order.car.comments' => ['nullable'],

            'meet.is_meet' => ['boolean'],
            'meet.place_id' => ['required_if:meet.is_meet,1,true'],
            'meet.place_type' => ['required_if:meet.is_meet,1,true'],
            'meet.number' => ['nullable'],
            'meet.text' => ['nullable'],
            'meet.metros' => ['nullable'],
            'meet.metro' => ['nullable'],
            'meet.airport' => ['nullable'],
            'meet.station' => ['nullable'],
            'meet.flight_number' => ['nullable'],
            'meet.wagon_number' => ['nullable'],
            'meet.from' => ['nullable'],

            'order.is_rent' => ['boolean'],
            'order.rent_time' => $this->all()['order']['is_rent'] ? ['required', 'in:'.implode(',', $rent_times)] : ['nullable']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function errorMessages(): array
    {
        return [
            'car.class.required' => 'Please check car type',
            'phone.client.required' => 'Please write client phone',
            'route.address_from.required' => 'Please check the address from',
            'order_time.required' => 'Please check the order time',
            'payment.type.required' => 'Please check Payment Type',
            'payment.company.exists' => 'This payment type dont found',
            'payment_type_id.company_has_tariff_region' => 'Ваша компания не может заплатить поездку в данном регионе',
        ];
    }
}
