<?php

declare(strict_types=1);

namespace Src\Http\Requests\Terminal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Src\Models\Debt\Debt;
use Src\Repositories\DriverWallet\DriverWalletContract;

/**
 * Class PayBalanceRequest
 * @property mixed cash
 * @package Src\Http\Requests\Terminal
 */
class PayBalanceRequest extends FormRequest
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
        $cash = app(DriverWalletContract::class)
            ->where('driver_id', '=', user()->{user()->getKeyName()})
            ->findFirst(['driver_id', 'transaction_cash']);

        return [
            'cash' => ['required', "lte:$cash->transaction_cash"]
        ];
    }
}
