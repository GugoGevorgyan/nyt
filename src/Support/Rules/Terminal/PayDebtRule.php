<?php

declare(strict_types=1);

namespace Src\Support\Rules\Terminal;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Validation\Rule;
use Src\Repositories\Driver\DriverContract;
use Src\Services\Terminal\TerminalServiceContract;

/**
 * Class PayDebtRule
 * @package Src\Support\Rules\Terminal
 */
class PayDebtRule implements Rule
{
    /**
     * @var Application|mixed|DriverContract
     */
    protected DriverContract $driverContract;
    /**
     * @var TerminalServiceContract
     */
    protected TerminalServiceContract $terminalService;
    /**
     * @var string
     */
    protected string $message = 'The validation error message.';
    /**
     * @var
     */
    protected $cash;
    /**
     * @var
     */
    protected $balance;

    /**
     * Create a new rule instance.
     *
     * @param $cash
     * @param $balance
     */
    public function __construct($cash, $balance)
    {
        $this->cash = $cash;
        $this->balance = $balance;

        $this->driverContract = app(DriverContract::class);
        $this->terminalService = app(TerminalServiceContract::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     * @noinspection MultipleReturnStatementsInspection
     */
    public function passes($attribute, $value): bool
    {
        $driver = $this->driverContract
            ->with(
                [
                    'cash' => fn ($query) => $query->select(['driver_id', 'cash_type', 'transaction_cash', 'balance', 'debt'])
                ]
            )
            ->find(get_user_id(), ['driver_id']);

        if (!$driver->cash->debt || $driver->cash->debt < 1) {
            $this->message = 'Driver is not debt';
            return false;
        }

        if ($this->balance && (!$driver->cash || !$driver->cash->balance)) {
            $this->message = 'You are not balance';
            return false;
        }

        if ($this->balance > $driver->cash->balance) {
            $this->message = 'Hacked data fuck off';
            return false;
        }

        if (($this->balance + $this->cash) < $driver->cash->minimal_repayment) {
            $this->message = 'Cash small then your minimal debt';
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
