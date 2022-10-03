<?php

declare(strict_types=1);

namespace Src\Http\Requests\Terminal;

use Illuminate\Foundation\Http\FormRequest;
use Src\Support\Rules\Terminal\PayDebtRule;

/**
 * Class PayDebtOffRequest
 * @property mixed cash
 * @property mixed selected_redemption
 * @property mixed debt
 * @property mixed deposit
 * @package Src\Http\Requests\Terminal
 */
class PayDebtOffRequest extends FormRequest
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
            'cash' => [new PayDebtRule($this->cash, $this->deposit)],
            'deposit' => [new PayDebtRule($this->cash, $this->deposit)]
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $this->replace(['deposit' => (float)$this->deposit, 'cash' => (float)$this->cash]);
        return $this->all();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'debt.required' => 'required pay',
            'debt.gte' => 'pay is small then minimal debt',
        ];
    }
}
