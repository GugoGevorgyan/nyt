<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateTrafficSafetyInspectionRequest
 * @package Src\Http\Requests\SystemWorker
 */
class UpdateTrafficSafetyInspectionRequest extends FormRequest
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
            'car_id' => 'required|exists:cars,car_id',
            'inspection_date' => 'required|date_format:Y-m-d|before:'.$this->request->get('inspection_expiration_date'),
            'inspection_expiration_date' => 'required|date_format:Y-m-d|after:'.$this->request->get('inspection_date'),
            'inspection_scan_file' => 'sometimes|mimes:jpeg,jpg,png',
        ];
    }
}
