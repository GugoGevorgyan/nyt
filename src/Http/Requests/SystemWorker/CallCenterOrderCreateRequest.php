<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstGuards;
use Src\Core\Enums\ConstRentTimes;
use Src\Http\Requests\BaseRequest;
use Src\Models\Order\PaymentType;

/**
 * Class CallCenterOrderCreateRequest
 * @package Src\Http\Requests\SystemWorker
 */
class CallCenterOrderCreateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::SYSTEM_WORKERS_WEB()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $request = $this->request->all();
        $passenger = isset($request['order']['is_passenger']) && $request['order']['is_passenger'];
        $rent_times = ConstRentTimes::getAll();

        $payCompany = PaymentType::where('type', '=', 2)->first();
        $company_id = $payCompany && $payCompany->payment_type_id === $request['order']['payment_type_id'] ? [
            'required',
            'exists:companies,company_id'
        ] : ['nullable'];

        $airport_id = isset($request['order']['is_meet']) &&
        $request['order']['is_meet'] &&
        !$request['orderMeet']['railway_station_id'] &&
        !$request['orderMeet']['metro_id'] ?
            'required|exists:airports,airport_id' : ['nullable'];
        $railway_station_id = isset($request['order']['is_meet']) &&
        $request['order']['is_meet'] &&
        !$request['orderMeet']['airport_id'] &&
        !$request['orderMeet']['metro_id'] ?
            'required|exists:railway_stations,railway_station_id' : ['nullable'];
        $metro_id = isset($request['order']['is_meet']) &&
        $request['order']['is_meet'] &&
        !$request['orderMeet']['airport_id'] &&
        !$request['orderMeet']['railway_station_id'] ?
            'required|exists:metros,metro_id' : ['nullable'];

        return [

            /*order*/
            'order.client_id' => ['required', 'exists:clients,client_id'],
            'order.company_id' => $company_id,
            'order.car_class_id' => ['nullable', 'exists:cars_class,car_class_id'],
            'order.payment_type_id' => ['required', 'exists:payment_types,payment_type_id'],
            'order.address_from' => 'required',
            'order.address_to' => 'nullable',
            'order.comments' => 'nullable',
            'order.car_option' => ['nullable', 'array'],
            'order.car_option.*' => ['nullable', 'exists:car_options,car_option_id'],
            'order.is_passenger' => ['nullable'],
            'order.is_meet' => ['nullable'],
            'order.is_preorder' => ['nullable'],
            'order.from_coordinates' => ['required'],
            'order.to_coordinates' => ['nullable'],
            'order.creating_type' => ['required', 'in:operator,dispatcher'],
            'order.order_id' => ['nullable'],
            'order.operator_id' => ['nullable'],
            'order.passenger_id' => ['nullable'],

            // Timed data
            'order.start_time' => ['required'],
            'order.create_time' => ['required'],
            'order.time_zone' => ['required'],
            'order.is_rent' => ['boolean'],
            'order.rent_time' => $this->all()['order']['is_rent'] ? ['required', 'in:'.implode(',', $rent_times)] : ['nullable'],

            // routes
            'route.from' => ['nullable'],
            'route.to' => ['nullable'],

            /*passenger*/
            'orderPassenger.phone' => $passenger ? ['required', 'min:6', 'max:100'] : ['nullable'],
            'orderPassenger.name' => $passenger ? ['nullable', 'max:100'] : ['nullable'],
            'orderPassenger.surname' => $passenger ? ['nullable', 'max:100'] : ['nullable'],
            'orderPassenger.patronymic' => $passenger ? ['nullable', 'max:100'] : ['nullable'],
            'orderPassenger.client_id' => $passenger ? ['required'] : ['nullable'],

            /*meet*/
            'orderMeet.airport_id' => $airport_id,
            'orderMeet.railway_station_id' => $railway_station_id,
            'orderMeet.metro_id' => $metro_id,
            'orderMeet.info' => ['nullable'],
            'orderMeet.text' => ['nullable'],
        ];
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return [];
    }

    protected function getValidatorInstance()
    {
        $data = $this->all();
        $data['order']['is_preorder'] = !($this->order['start_time'] === $this->order['create_time']);

        $this->getInputSource()->replace($data);
        return parent::getValidatorInstance();
    }
}
