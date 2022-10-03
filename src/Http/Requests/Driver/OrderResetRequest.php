<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OrderResetRequest
 *
 * @property $order_id
 * @property $hash
 * @property $assessment
 * @package Src\Http\Requests\Driver
 */
class OrderResetRequest extends FormRequest
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
            'hash' => ['required', 'exists:order_shipped_drivers,end_hash'],
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
            'assessment.required' => 'fewfewfwe',
            'assessment.max' => 'fewfewfwe',
            'assessment.min' => 'fewfewfwe',
            'assessment.digits' => 'fewfewfwe',
        ];
    }
}
