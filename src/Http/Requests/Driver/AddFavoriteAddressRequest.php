<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Core\Enums\ConstDriverAddressType;
use Src\Support\Rules\Cords\ValidLatitude;
use Src\Support\Rules\Cords\ValidLongitude;

/**
 * Class AddHomeRequest
 * @property string address
 * @property float lat
 * @property float lut
 * @property string target
 * @package Src\Http\Requests\Driver
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
            'address' => ['required', 'unique:driver_addresses,address'],
            'lat' => ['required', new ValidLatitude()],
            'lut' => ['required', new ValidLongitude()],
            'target' => ['in:'.ConstDriverAddressType::HOME()->getValue().','.ConstDriverAddressType::WORK()->getValue()],
        ];
    }
}
