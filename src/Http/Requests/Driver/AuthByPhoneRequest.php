<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Src\Core\Enums\ConstRedis;
use Src\Support\Rules\ExistsRedis;

/**
 * Class AuthByPhoneRequest
 * @package Src\Http\Requests\Driver
 */
class AuthByPhoneRequest extends FormRequest
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
            'phone' => ['required', 'exists:drivers,phone'],
            'accept_code' => ['required', new ExistsRedis(ConstRedis::accept_code('driver', $this->phone), 'accept_code')],
        ];
    }
}
