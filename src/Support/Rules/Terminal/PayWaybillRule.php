<?php

declare(strict_types=1);

namespace Src\Support\Rules\Terminal;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Validation\Rule;
use Src\Core\Enums\ConstDriverType;
use Src\Repositories\Driver\DriverContract;
use Src\Services\Terminal\TerminalServiceContract;

/**
 * Class PayWaybillRule
 * @package Src\Support\Rules\Terminal
 */
class PayWaybillRule implements Rule
{
    /**
     * @var Application|mixed|DriverContract
     */
    protected DriverContract $driverContract;
    /**
     * @var TerminalServiceContract|Application|mixed
     */
    protected TerminalServiceContract $terminalService;
    /**
     * @var string
     */
    protected string $message = 'The validation error message.';

    /**
     * Create a new rule instance.
     *
     * @param $cash
     * @param $balance
     * @param $days
     */
    public function __construct(protected $cash, protected $balance, protected $days)
    {
        $this->driverContract = app(DriverContract::class);
        $this->terminalService = app(TerminalServiceContract::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @noinspection MultipleReturnStatementsInspection
     */
    public function passes($attribute, $value): bool
    {
        $is_corporate = $this->driverContract
            ->where('driver_id', '=', get_user_id())
            ->whereHas('active_contract', fn($query) => $query->where('driver_type_id', '=', ConstDriverType::CORPORATE()->getValue()))
            ->exists();

        session()->now('is_corporate', $is_corporate);

        if ($is_corporate) {
            return true;
        }

        $driver = $this->driverContract
            ->with([
                'cash' => fn($query) => $query->select(['driver_id', 'cash_type', 'transaction_cash', 'balance']),
                'active_contract' => fn($query) => $query->select(['free_days_price', 'busy_days_price', 'driver_id']),
            ])
            ->find(get_user_id(), ['driver_id']);

        //@todo
        if ($driver->cash->debt && $driver->debt->amount_debt) {
            $this->message = 'You are debt please pay debt';
            return false;
        }

        $all_cash = $this->cash + $this->balance;

        if ($this->cash > $driver->cash->transaction_cash) {
            $this->message = "Yor not a cash $this->cash your cash is {$driver->cash->transaction_cash}";
            return false;
        }

        if ($this->balance > $driver->cash->balance) {
            $this->message = 'Hacked data';
            return false;
        }

        if ($driver->cash->debt && $all_cash < $driver->cash->min_repayment_waybill) {
            $waybill_debt = $driver->cash->min_repayment_waybill + $driver->cash->min_repayment;
            $this->message = "Waybill minimal paid price $waybill_debt";
            return false;
        }

        $waybill_price = $driver->last_active_waybill
            ? $this->terminalService->getDriverLatestComingWaybill($driver->driver_id, $driver->active_contract)
            : 0.00;

        if (!$driver->cash->debt && $all_cash < $waybill_price) {
            $this->message = "Waybill price as {$driver->active_contract->busy_days_price}";
            return false;
        }

        if (!$this->days) {
            return false;
        }

        if (!$this->terminalService->checkDriverActiveWaybillsLimit($driver->driver_id, $this->days)) {
            $this->message = trans('messages.worker_active_waybills_reached');
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
