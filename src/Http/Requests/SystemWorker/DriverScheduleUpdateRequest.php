<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DriverScheduleUpdateRequest
 * @package Src\Http\Requests\SystemWorker
 */
class DriverScheduleUpdateRequest extends FormRequest
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
            'driver_id' => ['required', 'exists:drivers,driver_id'],
            'date' => ['required', 'date_format:Y-m-d', 'after:yesterday']
        ];
    }
}
