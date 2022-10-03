<?php

declare(strict_types=1);

namespace Src\Support\Rules\Driver;

use Illuminate\Contracts\Validation\Rule;
use Src\Repositories\Driver\DriverContract;

/**
 * Class DriverHasCurrentAnnulledWaybillRule
 * @package Src\Support\Rules\Terminal
 */
class DriverHasCurrentAnnulledWaybillRule implements Rule
{
    /**
     * @var DriverContract
     */
    protected DriverContract $driverContract;

    /**
     * DriverHasCurrentAnnulledWaybillRule constructor.
     */
    public function __construct()
    {
        $this->driverContract = app(DriverContract::class);
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $driver = $this->driverContract
            ->with('current_waybill')
            ->find($value);

        return $driver->current_waybill && $driver->current_waybill->deleted_at;
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
