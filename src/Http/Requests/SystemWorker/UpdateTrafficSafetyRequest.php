<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateTrafficSafetyRequest
 * @package Src\Http\Requests\SystemWorker
 */
class UpdateTrafficSafetyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::check(); /* Auth::user()->hasRole(Role::WORKER_PERSONAL_DEPARTMENT_WEB);*/
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $yesterday_year = Carbon::yesterday()->format('Y');

        return [
            'entity_id' => 'required|exists:legal_entities,legal_entity_id',
            'class_ids' => 'required|array',
            'class_ids.*' => 'required|exists:cars_class,car_class_id',
            'mark' => 'required|max:100',
            'model' => 'required|max:100',
            'year' => 'required|integer|min:1900|max:' . $yesterday_year,
            'status_id' => 'required|exists:car_statuses,car_status_id',
            'insurance_date' => 'nullable|date_format:Y-m-d|before:'.$this->request->get('insurance_expiration_date'),
            'insurance_expiration_date' => 'nullable|date_format:Y-m-d|after:'.$this->request->get('insurance_date'),
            'insurance_scan_file' => 'nullable|mimes:jpeg,jpg,png',
            'inspection_date' => 'nullable|date_format:Y-m-d|before:'.$this->request->get('inspection_expiration_date'),
            'inspection_expiration_date' => 'nullable|date_format:Y-m-d|after:'.$this->request->get('inspection_date'),
            'inspection_scan_file' => 'nullable|mimes:jpeg,jpg,png',
            'vin_code' => 'required|min:6|max:32',
            'body_number' => 'nullable|min:6|max:32',
            'vehicle_licence_number' => 'required|min:6|max:32',
            'vehicle_licence_date' => 'nullable|date_format:Y-m-d',
            'sts_number' => ['nullable', 'min:6', 'max:32'],
            'pts_number' => ['nullable', 'min:6', 'max:32'],
            'sts_file' => ['nullable', 'array'],
            'pts_file' => ['nullable', 'array'],
            'images' => ['nullable', 'array'],
            'registration_date' => 'required|date_format:Y-m-d',
            'color' => 'required|max:100',
            'state_license_plate' => 'required|max:9',
            'speedometer' => 'required|max:100',
            'garage_number' => 'required|max:100'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }
}
