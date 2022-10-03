<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ToggleCarClassRequest
 * @package Src\Http\Requests\Driver
 */
class ToggleCarClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'class_id' => ['required', 'exists:cars_class,car_class_id'],
        ];
    }
}
