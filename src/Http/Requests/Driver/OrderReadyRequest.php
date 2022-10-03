<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Auth;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;

/**
 * Class OrderReadyRequest
 * @property mixed $ready
 * @package Src\Http\Requests\Driver
 */
class OrderReadyRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::DRIVERS_API()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ready' => ['required', 'boolean'],
            'lat' => ['required'],
            'lut' => ['required'],
        ];
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return [
            'ready.required' => 'ready status is required',
            'ready.boolean' => 'ready data type invalid',
        ];
    }
}
