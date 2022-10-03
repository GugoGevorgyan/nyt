<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Src\Http\Requests\BaseRequest;
use Src\Support\Rules\Cords\ValidLatitude;
use Src\Support\Rules\Cords\ValidLongitude;

/**
 *
 * @property mixed $lat
 * @property mixed $lut
 */
class CoordinatesByAddressRequest extends BaseRequest
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
            'lut' => ['required', new ValidLongitude()],
        ];
    }

    public function errorMessages(): array
    {
        return [];
    }

    public function prepareForValidation(): void
    {
        $this->merge(['lat' => $this->lat, 'lut' => $this->lut]);
    }
}
