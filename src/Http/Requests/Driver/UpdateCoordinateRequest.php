<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateCoordinateRequest
 * @property mixed lat
 * @property mixed lut
 * @package Src\Http\Requests\Driver
 */
class UpdateCoordinateRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'lat' => 'required', // @TODO FIX valid_address filter
            'lut' => 'required',
        ];
    }
}
