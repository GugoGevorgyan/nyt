<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Auth;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;
use Src\Core\Enums\ConstGuards;
use Src\Support\Rules\Driver\CommonOrderHasDriverRule;

/**
 * Class CommonOrderRequest
 * @property mixed $order_id
 * @property mixed $hash
 * @property mixed $accept
 * @package Src\Http\Requests\Driver
 */
class CommonOrderRequest extends FormRequest
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
     * @throws BindingResolutionException
     */
    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'exists:orders,order_id',
                new CommonOrderHasDriverRule(user()->{user()->getKeyName()}, $this->order_id),
            ],
            'hash' => ['required', 'exists:order_shipped_drivers,accept_hash'],
            'accept' => ['required', 'bool']
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
            'order_id.required' => '',
            'order_id.exists' => trans('validation.custom.rules.order_not_exists'),
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge(['order_id' => (int)$this->order_id, 'hash' => $this->hash, 'accept' => (bool)$this->accept]);
    }
}
