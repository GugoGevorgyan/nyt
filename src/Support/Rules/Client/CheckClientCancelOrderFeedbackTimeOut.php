<?php

declare(strict_types=1);

namespace Src\Support\Rules\Client;

use Src\Repositories\CanceledOrder\CanceledOrderContract;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class CheckClientCancelOrderFeedbackTimeOut
 * @package Src\Support\Rules
 */
class CheckClientCancelOrderFeedbackTimeOut implements Rule
{
    protected $abortedOrderContract;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->abortedOrderContract = app(CanceledOrderContract::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $abort_time = $this->abortedOrderContract->find($value, [$this->abortedOrderContract->getKeyName(), 'created_at']);

        return !$abort_time ?  true : !(Carbon::now()->diffInSeconds($abort_time->created_at) >= 5000);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
