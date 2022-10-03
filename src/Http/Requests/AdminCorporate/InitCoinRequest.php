<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminCorporate;

use Auth;
use Src\Core\Enums\ConstGuards;
use Src\Core\Enums\ConstRentTimes;
use Src\Http\Requests\BaseRequest;

/**
 * Class InitCoinRequest
 * @property mixed client_id
 * @property mixed $is_rent
 * @package Src\Http\Requests\AdminCorporate
 */
class InitCoinRequest extends BaseRequest
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
            'client_id' => ['required', 'exists:clients,client_id'],
            'phone.client' => ['required', 'exists:clients,phone'],
            'payment.type' => ['required', 'exists:payment_types,payment_type_id'],
            'payment.company' => ['required', 'exists:companies,company_id'],
            'company_id' => ['required'],

            'status' => ['nullable'],
            'message' => ['nullable'],

            'route.from_address' => ['required'],
            'route.to_address' => ['nullable'],
            'route.from' => ['required'],
            'route.to' => ['nullable'],

            'car.options.*' => ['nullable', 'exists:car_options,car_option_id'],
            'car.comment' => ['nullable'],
            'car.comments' => ['nullable'],
            'car.options' => ['nullable', 'array'],
            'car.class' => ['nullable', 'exists:cars_class,car_class_id'],

            'time' => ['required', 'array'],
            'time.create_time' => ['required', 'date', 'date_format:Y-m-d H:i', 'before_or_equal:now'],
            'time.time' => ['required', 'required', 'date', 'date_format:Y-m-d H:i'],
            'time.zone' => ['required', 'timezone', 'string'],

            'orderMeet.vagon_number' => ['nullable'],
            'orderMeet.flight_number' => ['nullable'],
            'orderMeet.from' => ['nullable'],

            'is_rent' => 'boolean',
            'rent_time' => $this->all()['is_rent'] ? ['required', 'in:'.implode(',', $rent_times)] : ['nullable']
        ];
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return [];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['phone' => ['client' => preg_replace('/\D/', '', $this['phone']['client'])]]);
    }
}
