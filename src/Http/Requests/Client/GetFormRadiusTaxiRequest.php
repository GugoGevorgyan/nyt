<?php

declare(strict_types=1);

namespace Src\Http\Requests\Client;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Support\Rules\Cords\ValidLatitude;
use Src\Support\Rules\Cords\ValidLongitude;

/**
 * @property mixed lat
 * @property mixed lut
 */
class GetFormRadiusTaxiRequest extends FormRequest
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
            'lut' => ['required', new ValidLongitude(), 'valid_address:'.$this->validationData()['lat']],
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData(): array
    {
        $this->request->replace(['lat' => (float)$this->lat, 'lut' => (float)$this->lut]);
        return $this->all();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'lat.required' => 'Coordinates is required field',
            'lut.required' => 'Coordinates is required field',
            'lat.valid_address' => 'Coordinates is not valid',
            'lut.valid_address' => 'Coordinates is not valid',
        ];
    }
}
