<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Src\Support\Rules\Driver\PrepareOrderDriverRule;

/**
 * Class PrepareCommonOrderRequest
 * @property mixed order_id
 * @package Src\Http\Requests\Driver
 */
class PrepareCommonOrderRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'exists:orders,order_id',
                new PrepareOrderDriverRule(user()->{user()->getKeyName()}, $this->order_id),
            ]
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge(['order_id' => (int)$this->order_id]);
    }
}
