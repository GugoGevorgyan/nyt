<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Car\Car;
use Src\Models\Driver\Driver;
use Src\Models\Role\Role;

/**
 * Class CreateCrashRequest
 * @package Src\Http\Requests\SystemWorker
 */
class CreateCrashRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && (user()->is_admin || Auth::user()->hasRole(Role::WORKER_PERSONAL_DEPARTMENT_WEB));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'car_id' => ['required', 'in:'.Car::where('franchise_id', FRANCHISE_ID)->get()->implode('car_id', ',')],
            'driver_id' => ['required', 'in:'.Driver::where('current_franchise_id', FRANCHISE_ID)->get()->implode('driver_id', ',')],
            'address' => ['required', 'min:3', 'max:100'],
            'dateTime' => ['required', 'date_format:Y-m-d H:i:s', 'before:today'],
            'description' => ['nullable'],
            'our_fault' => ['required', 'boolean'],
            'act_sum' => ['nullable'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png'],
        ];
    }
}
