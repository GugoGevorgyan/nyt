<?php

declare(strict_types=1);

namespace Src\Support\Rules\Driver;

use Illuminate\Contracts\Validation\Rule;
use Src\Repositories\Driver\DriverContract;

/**
 * Class DriverStatusCheckRule
 * @package Src\Support\Rules\Order
 */
class DriverStatusCheckRule implements Rule
{
    protected array $statuses;

    protected DriverContract $driverContract;

    protected string $message;

    /**
     * Create a new rule instance.
     *
     * @param  array  $statuses
     */
    public function __construct(array $statuses)
    {
        $this->statuses = $statuses;
        $this->driverContract = app(DriverContract::class);
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
        if (!$this->driverContract->whereIn('current_status_id',$this->statuses)->find(user()->{user()->getKeyName()},['driver_id', 'current_status_id'])) {
            $this->message = (string)trans('validation.custom.rules.driver_reject_order');
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
