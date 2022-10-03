<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Foundation\Http\FormRequest;
use Src\Support\Rules\Cords\ValidLatitude;
use Src\Support\Rules\Cords\ValidLongitude;

/**
 * Class EditAddressRequest
 * @property mixed payload
 * @package Src\Http\Requests\ClientMobile
 */
class EditAddressRequest extends FormRequest
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
    public function rules()
    {
        return [
            'payload.client_address_id' => ['required', 'exists:client_addresses,client_address_id'],
            'payload.name' => ['required', 'string'],
            'payload.short_address' => ['required', 'string'],
            'payload.address' => ['required', 'string'],
            'payload.favorite' => ['required', 'boolean'],
            'payload.cord.lat' => ['required', new ValidLatitude()],
            'payload.cord.lut' => ['required', new ValidLongitude()],
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $this->request->replace([
            'payload' => [
                'client_address_id' => $this->address_id,
                'name' => $this->name,
                'short_address' => $this->short_address,
                'address' => $this->address,
                'favorite' => (boolean)$this->favorite,
                'cord' => [
                    'lat' => $this->cord['lat'],
                    'lut' => $this->cord['lut'],
                ],
            ]
        ]);

        return $this->request->all();
    }
}
