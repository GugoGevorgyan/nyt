<?php

declare(strict_types=1);


namespace Src\WebSocket\Requests\Driver;


use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CoordinateUpdateRequest
 * @package Src\WebSocket\Request\Driver
 */
class CoordinateUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'user' => 'required',
            'data.lat' => 'required',
            'data.lut' => 'required',
        ];
    }
}
