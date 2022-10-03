<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateTrafficSafetyInsuranceRequest
 * @package Src\Http\Requests\SystemWorker
 */
class UpdateTrafficSafetyInsuranceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'car_id' => 'required|exists:cars,car_id',
            'insurance_date' => 'required|date_format:Y-m-d|before:'.$this->request->get('insurance_expiration_date'),
            'insurance_expiration_date' => 'required|date_format:Y-m-d|after:'.$this->request->get('insurance_date'),
            'insurance_scan_file' => 'sometimes|mimes:jpeg,jpg,png',
        ];
    }
}
