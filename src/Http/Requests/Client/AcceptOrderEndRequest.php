<?php

declare(strict_types=1);

namespace Src\Http\Requests\Client;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AcceptOrderEndRequest
 * @property mixed accept
 * @property mixed order_id
 * @package Src\Http\Requests\Client
 */
class AcceptOrderEndRequest extends FormRequest
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
            'order_id' => ['required', 'exists:orders,order_id'],
            'accept' => ['required', 'boolean']
        ];
    }
}
