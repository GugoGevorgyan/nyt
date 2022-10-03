<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use JetBrains\PhpStorm\ArrayShape;
use Src\Core\Enums\ConstGuards;
use Src\Core\Enums\ConstRentTimes;
use Src\Http\Requests\BaseRequest;
use Src\Support\Rules\Cords\ValidLatitude;
use Src\Support\Rules\Cords\ValidLongitude;

/**
 * Class GetOrderPriceRequest
 * @property mixed address_from
 * @property mixed address_to
 * @property mixed options
 * @property mixed payment_type
 * @property mixed payment_company
 * @property mixed car_class
 * @property mixed car_options
 * @property mixed order_time
 * @property mixed route
 * @property mixed time
 * @package Src\Http\Requests
 */
class GetOrderPriceRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && get_guard() === ConstGuards::CLIENTS_API()->getValue();
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
            'phone.client' => ['required'],
            'phone.passenger' => ['nullable'],

            'route' => [
                'from' => [
                    'required',
                    (new ValidLatitude())->passes('route.from', $this->route['from'][0]),
                    (new ValidLongitude())->passes('route.from', $this->route['from'][1])
                ],
                'to' => [
                    'nullable',
                    (new ValidLongitude())->passes('route.to', $this->route['to'][0] ?? 0),
                    (new ValidLongitude())->passes('route.to', $this->route['to'][1] ?? 0)
                ],
                'from_address' => ['required'],
                'to_address' => ['nullable'],
            ],

            'time' => ['required', 'array'],
            'time.create_time' => ['required'/*, 'date', 'date_format:Y-m-d H:i:s'*/],
            'time.time' => ['required', 'date'/*, 'date_format:Y-m-d H:i:s'*/],
            'time.zone' => ['required', 'timezone'],

            'payment.type' => [
                'required',
                'exists:payment_types,payment_type_id'
            ],
            'payment.company' => [
                'nullable',
                'required_if:payment_type,2',
                'company_has_tariff_region:'.$this->all()['route']['from'][0].','.$this->all()['route']['from'][1]
            ],
            'payment.card' => [
                'nullable',
                'required_if:payment_type,3',
            ],

            'car.options' => ['nullable'],
            'car.class' => 'required',
            'car.comments' => ['nullable'],

            'meet.is_meet' => ['boolean'],
            'meet.place_id' => ['required_if:meet.is_meet,1,true'],
            'meet.place_type' => ['required_if:meet.is_meet,1,true'],
            'meet.number' => ['nullable'],
            'meet.text' => ['nullable'],

            'is_rent' => ['boolean'],
            'rent_time' => $this->all()['is_rent'] ? ['required', 'in:'.implode(',', $rent_times)] : ['nullable']
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $new_users_data = $this->time;
        $new_users_data['zone'] = get_nearest_timezone($this->route['from'][0], $this->route['from'][1]);

        $this->merge(['time' => $new_users_data, 'is_rent' => $this['is_rent'] ?? false]);

        parent::prepareForValidation();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    #[ArrayShape([
        'phone.client.required' => 'string',
        'route.address_from.required' => 'string',
        'car.class.required' => 'string',
        'order_time.required' => 'string',
        'payment.type.required' => 'string',
        'payment.company.exists' => 'string',
        'payment_type_id.company_has_tariff_region' => 'string'
    ])] public function errorMessages(): array
    {
        return [
            'phone.client.required' => 'Please write client phone',
            'route.address_from.required' => 'Please check the address from',
            'car.class.required' => 'Please check car type',
            'order_time.required' => 'Please check the order time',
            'payment.type.required' => 'Please check Payment Type',
            'payment.company.exists' => 'This payment type dont found',
            'payment_type_id.company_has_tariff_region' => 'Ваша компания не может заплатить поездку в данном регионе',
        ];
    }

    /**
     * @param  Validator  $validator
     */
    public function moreValidation(Validator $validator): void
    {
        $validator->after(function () use ($validator) {
            if (!$this->request->count()) {
                $validator->errors()->add('payload', 'empty request body');
            }
        });
    }

}
