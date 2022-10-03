<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Support\Rules\Cords\ValidLatitude;

/**
 * Class SelectFavoriteRequest
 * @property int address_id
 * @property float lat
 * @property float lut
 * @property array cords
 * @package Src\Http\Requests\Driver
 */
class SelectFavoriteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'lat' => ['required', new ValidLatitude()],
            'lut' => ['required', new ValidLatitude()],
            'address_id' => ['required', 'exists:driver_addresses,driver_address_id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'lat.required' => '',
            'lut.required' => '',
            'address_id.required' => '',
            'address_id.exists' => '',
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData(): array
    {
        $this->request->add(['cords' => ['lat' => (float)$this->lat, 'lut' => (float)$this->lut], 'address_id' => (int)$this->address_id]);
        return $this->all();
    }
}
