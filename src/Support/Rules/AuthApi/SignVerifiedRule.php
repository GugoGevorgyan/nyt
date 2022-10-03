<?php

declare(strict_types=1);

namespace Src\Support\Rules\AuthApi;

use Illuminate\Contracts\Validation\Rule;
use Src\Services\Payment\PaymentService;

/**
 * Class SignVerifiedRule
 * @package Src\Support\Rules\AuthApi
 */
class SignVerifiedRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $payment_service = app(PaymentService::class);

        return $payment_service->isResponseSign(request()->id, request()->status, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute is not valid.';
    }
}
