<?php

declare(strict_types=1);

namespace Src\Http\Requests\Terminal;

use Illuminate\Support\Facades\Auth;
use Src\Http\Requests\BaseRequest;
use Src\Support\Rules\Terminal\PayWaybillRule;

/**
 * @property mixed cash
 * @property mixed deposit
 * @property mixed days
 */
class PayWaybillRequest extends BaseRequest
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
            'cash' => [new PayWaybillRule($this->cash, $this->deposit, $this->days)],
            'deposit' => [new PayWaybillRule($this->cash, $this->deposit, $this->days)],
            'days' => [new PayWaybillRule($this->cash, $this->deposit, $this->days)]
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return void
     */
    public function prepareForValidation()
    {
        $this->merge(['cash' => (float)$this->cash, 'deposit' => (float)$this->deposit, 'days' => (int)$this->days]);
    }

    public function errorMessages(): array
    {
        return [];
    }
}
