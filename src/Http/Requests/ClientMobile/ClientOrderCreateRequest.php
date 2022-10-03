<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Src\Core\Enums\ConstGuards;
use Src\Core\Enums\ConstRentTimes;
use Src\Http\Requests\BaseRequest;
use Src\Repositories\Timezone\TimezoneContract;

/**
 * Class ClientOrderCreateRequest
 * @package Src\Http\Requests\ClientMobile
 */
class ClientOrderCreateRequest extends BaseRequest
{
    /**
     * @param  TimezoneContract  $timezoneContract
     */
    public function __construct(protected TimezoneContract $timezoneContract)
    {
        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::CLIENTS_API()->getValue())->check();
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
                'from' => 'required',
                'to' => ['nullable'],
                'from_address' => 'required',
                'to_address' => ['nullable'],
            ],

            'time' => ['required', 'array'],
            'time.create_time' => ['required', 'date', 'date_format:Y-m-d H:i'],
            'time.time' => ['required', 'required', 'date', 'date_format:Y-m-d H:i'],
            'time.zone' => ['required', 'timezone'],

            'payment.type' => ['required', 'exists:payment_types,payment_type_id'],
            'payment.company' => [
                'required_if:payment_type,2exists:payment_types,payment_type_id',
                'company_has_tariff_region:'.$this->validationData()['route']['from'][0].','.$this->validationData()['route']['from'][1]
            ],
            'payment.card' => [
                'required_if:payment_type,3',
                'exists:payment_types,payment_type_id',
            ],

            'car.options' => ['nullable'],
            'car.class' => ['required'],
            'car.comments' => ['nullable'],

            'meet.is_meet' => ['boolean'],
            'meet.place_id' => ['required_if:meet.is_meet,1,true'],
            'meet.place_type' => ['required_if:meet.is_meet,1,true'],
            'meet.number' => ['nullable'],
            'meet.text' => ['nullable'],

            'is_rent' => ['boolean'],
            'rent_time' => $this->all()['is_rent'] ? ['required', 'in:'.implode(',', $rent_times)] : ['nullable'],

            // DYNAMIC
            'customer_zone_id' => ['nullable'],
            'location_zone_id' => ['nullable'],
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

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $location_zone = trim(get_nearest_timezone($this->route['from'][0], $this->route['from'][1]) ?? '');
        $customer_zone = trim($this->time['zone'] ?? '');

        $time_data = $this->time;
        $time_data['zone'] = $location_zone;

        $location_zone_id = $this->timezoneContract->where('zone_string', '=', $location_zone)->findFirst('timezone_id')->timezone_id ?? null;
        $customer_zone_id = $this->timezoneContract->where('zone_string', '=', $customer_zone)->findFirst('timezone_id')->timezone_id ?? null;

        $this->merge([
            'time' => $time_data,
            'customer_zone_id' => $customer_zone_id,
            'location_zone_id' => $location_zone_id
        ]);

        parent::prepareForValidation();
    }
}
