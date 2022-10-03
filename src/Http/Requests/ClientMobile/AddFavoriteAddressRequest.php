<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Foundation\Http\FormRequest;
use Src\Support\Rules\Cords\ValidLatitude;
use Src\Support\Rules\Cords\ValidLongitude;

/**
 * Class AddFavoriteAddressRequest
 * @property mixed payload
 * @property mixed name
 * @property mixed value
 * @property mixed address
 * @property mixed cord
 * @property mixed favorite
 * @package Src\Http\Requests\ClientMobile
 */
class AddFavoriteAddressRequest extends FormRequest
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
                'name' => $this->name,
                'short_address' => $this->short_address,
                'address' => $this->address,
                'cord' => [
                    'lat' => $this->cord['lat'],
                    'lut' => $this->cord['lut'],
                ],
                'favorite' => (boolean)$this->favorite,
            ]
        ]);

        return $this->request->all();
    }
}
