<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;
use Src\Core\Enums\ConstGuards;
use Src\Support\Rules\Cords\ValidLatitude;
use Src\Support\Rules\Cords\ValidLongitude;

/**
 * Class OpenAppRequest
 * @property mixed lat
 * @property mixed lut
 * @package Src\Http\Requests\ClientMobile
 */
class OpenAppRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::CLIENTS_API()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['lat' => 'array', 'lut' => 'array'])] public function rules(): array
    {
        return [
            'lat' => ['required', new ValidLatitude()],
            'lut' => ['required', new ValidLongitude()],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    #[ArrayShape(['lat.required' => 'string', 'lut.required' => 'string'])] public function messages(): array
    {
        return [
            'lat.required' => '',
            'lut.required' => '',
        ];
    }
}
