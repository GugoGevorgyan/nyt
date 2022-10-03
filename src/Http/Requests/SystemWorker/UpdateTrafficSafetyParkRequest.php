<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateTrafficSafetyParkRequest
 * @package Src\Http\Requests\SystemWorker
 */
class UpdateTrafficSafetyParkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::check(); /*Auth::user()->hasRole(Role::WORKER_PERSONAL_DEPARTMENT_WEB);*/
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'park_id' => 'nullable|exists:parks,park_id',
            'car_id' => 'required|exists:cars,car_id'
        ];
    }
}
