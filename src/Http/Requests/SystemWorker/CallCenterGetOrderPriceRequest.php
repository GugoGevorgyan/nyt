<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstRentTimes;
use Src\Models\Order\PaymentType;

/**
 *
 */
class CallCenterGetOrderPriceRequest extends FormRequest
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
        $data = $this->request->all();
        $rent_times = ConstRentTimes::getAll();

        return [
            'client_id' => ['required', 'exists:clients,client_id'],
            'payment.type' => ['required', 'exists:payment_types,payment_type_id'],
            'payment.company' => isset($data['payment_type_id']) && $data['payment_type_id'] === PaymentType::COMPANY?
                ['required', 'exists:companies,company_id']: ['nullable'],

            'route.from_address' => ['required'],
            'route.to_address' => ['nullable'],
            'route.from' => ['required', 'array'],
            'route.from.*' => 'required',
            'route.to' => 'nullable',

            'car.options' => ['nullable', 'array'],
            'car.options.*' => 'nullable|exists:car_options,car_option_id',
            'car.comment' => ['nullable'],
            'car.class' => ['nullable', 'exists:cars_class,car_class_id'],

            'time' => ['required', 'array'],
            'time.create_time' => ['required'/*, 'date', 'date_format:Y-m-d H:i:s'*/],
            'time.time' => ['required', 'date'/*, 'date_format:Y-m-d H:i:s'*/],
            'time.zone' => ['required', 'timezone'],

            'is_rent' => 'boolean',
            'rent_time' => $this->all()['is_rent'] ? ['required', 'in:'.implode(',', $rent_times)] : ['nullable']

            /*'meet.vagon_number' => 'nullable',
            'meet.flight_number' => 'nullable',
            'meet.from' => 'nullable'*/
        ];
    }
}
