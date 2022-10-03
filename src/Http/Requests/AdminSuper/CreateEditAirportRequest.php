<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateEditAirportRequest
 * @package Src\Http\Requests\AdminSuper
 */
class CreateEditAirportRequest extends FormRequest
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
            'name' => ['required', 'min:3'],
            'terminal' => ['required', 'min:3'],
            'city' => ['required', 'exists:cities,city_id'],
            'address' => ['required'],
            'cord' => ['required', 'array'],
        ];
    }
}
