<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstGuards;

/**
 * Class OrderCancelRequest
 * @package Src\Http\Requests\ClientMobile
 */
class OrderCancelRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            //
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

        ];
    }
}
