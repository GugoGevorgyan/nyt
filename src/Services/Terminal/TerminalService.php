<?php

declare(strict_types=1);


namespace Src\Services\Terminal;


use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\ArrayShape;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ServiceEntity\BaseService;
use Src\Broadcasting\Broadcast\Driver\WaybillAnnulled;
use Src\Core\Enums\ConstTransactionType;
use Src\Exceptions\Lexcept;
use Src\Models\Driver\Driver;
use Src\Models\Order\PaymentType;
use Src\Repositories\DebtRepayment\DebtRepaymentContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverContract\DriverContractContract;
use Src\Repositories\DriverDebt\DebtContract;
use Src\Repositories\DriverInfo\DriverInfoContract;
use Src\Repositories\DriverSchedule\DriverScheduleContract;
use Src\Repositories\DriverWallet\DriverWalletContract;
use Src\Repositories\FranchiseTransaction\FranchiseTransactionContract;
use Src\Repositories\Terminal\TerminalContract;
use Src\Repositories\Waybill\WaybillContract;
use Src\Services\Worker\WorkerServiceContract;

/**
 * Class TerminalService
 * @package Src\Services\Terminal
 */
final class TerminalService extends BaseService implements TerminalServiceContract
{
    /**
     * TerminalService constructor.
     * @param  DriverContract  $driverContract
     * @param  DebtRepaymentContract  $repaymentContract
     * @param  DriverInfoContract  $driverInfoContract
     * @param  DriverWalletContract  $driverWalletContract
     * @param  WaybillContract  $waybillContract
     * @param  WorkerServiceContract  $workerService
     * @param  FranchiseTransactionContract  $transactionContract
     * @param  DriverScheduleContract  $driverScheduleContract
     * @param  TerminalContract  $terminalContract
     * @param  DriverContractContract  $driverContractContract
     * @param  DebtContract  $debtContract
     */
    public function __construct(
        protected DriverContract $driverContract,
        protected DebtRepaymentContract $repaymentContract,
        protected DriverInfoContract $driverInfoContract,
        protected DriverWalletContract $driverWalletContract,
        protected WaybillContract $waybillContract,
        protected WorkerServiceContract $workerService,
        protected FranchiseTransactionContract $transactionContract,
        protected DriverScheduleContract $driverScheduleContract,
        protected TerminalContract $terminalContract,
        protected DriverContractContract $driverContractContract,
        protected DebtContract $debtContract,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getDriverDebt(int $driver_id): ?array
    {
        $driver = $this->getDriverDataWithActiveWaybill($driver_id);
        $current_waybill_price = $driver->last_active_waybill ? $this->getDriverUnpaidDaysPrice($driver) : 0.0;

        $this->driverWalletContract
            ->where('driver_id', '=', $driver_id)
            ->updateSet(['min_repayment_waybill' => $current_waybill_price]);
        $waybills_allowed_limit = $this->maximumAllowableQuantityWaybills($driver_id);

        if (!$driver->cash->debt) {
            return [
                'repayment_waybill' => $current_waybill_price,
                'balance' => $driver->cash->balance ?? 0.0,
                'transaction_cash' => $driver->cash->transaction_cash ?? 0.0,
                'waybills_allowed_limit' => $waybills_allowed_limit
            ];
        }

        if (!$this->driverWalletContract->where('driver_id', '=', $driver_id)->has('repayment')->exists('driver_wallet_id')) {
            return $this->getDebtCalculate($driver, $current_waybill_price);
        }

        return [
            'debt' => $driver->cash->debt,
            'amount' => $driver->cash->repayment->amount,
            'repayment_amount' => $driver->cash->min_repayment,
            'repayment_waybill' => $current_waybill_price,
            'balance' => $driver->cash->balance ?? 0.0,
            'transaction_cash' => $driver->cash->transaction_cash ?? 0.0,
            'waybills_allowed_limit' => $waybills_allowed_limit
        ];
    }

    /**
     * @param $driver_id
     * @return object|null
     */
    protected function getDriverDataWithActiveWaybill($driver_id): ?object
    {
        return $this->driverContract
            ->with([
                'last_active_waybill',
                'active_contract' => fn($query) => $query->select(['driver_id', 'driver_contract_id', 'free_days_price', 'busy_days_price']),
                'cash' => fn($query) => $query->select(['driver_id', 'balance', 'transaction_cash', 'debt', 'repayment_id', 'min_repayment_waybill'])
            ])
            ->find($driver_id, ['driver_id']);
    }

    /**
     * @param $driver
     * @return float|int
     */
    protected function getDriverUnpaidDaysPrice($driver): float|int
    {
        $todayIsUnpaid = now() > $driver->last_active_waybill->end_time;
        $scheduleOfToday = $todayIsUnpaid
            ? $this->getScheduleUnpaidOfToday($driver, $driver->last_active_waybill->end_time)
            : [];


        $schedule_of_unpaid_days = count($this->getScheduleOfUnpaidDays($driver, now()))
            ? $this->getScheduleOfUnpaidDays($driver, now())
            : $scheduleOfToday;
        $unpaidDaysCount = count($schedule_of_unpaid_days);
        $prices = [];

        if (($unpaidDaysCount > 0) && isset($schedule_of_unpaid_days)) {
            foreach ($schedule_of_unpaid_days as $schedule) {
                $prices[] = [
                    'date' => $schedule->date_time,
                    'amount' => $schedule->working
                        ? $driver->active_contract->busy_days_price
                        : $driver->active_contract->free_days_price
                ];
            }
        }

        return array_sum(array_column($prices, 'amount'));
    }

    /**
     * @param  Driver  $driver
     * @param  $from_date
     * @param  int  $days
     * @return Collection
     */
    public function getScheduleUnpaidOfToday($driver, $from_date, int $days = 1)
    {
        return $this->driverScheduleContract
            ->where('driver_id', '=', $driver->driver_id)
            ->where('driver_contract_id', '=', $driver->active_contract->driver_contract_id)
            ->where('date', '>=', $from_date->format('Y-m-d'))
            ->where('date', '<', Carbon::parse($from_date)->addDays($days)->format('Y-m-d'))
            ->findAll([
                'driver_schedule_id',
                'driver_id',
                'driver_contract_id',
                'working',
                DB::raw("CONCAT(`date`, ' ".Carbon::parse($from_date)->format('H:i:s')."') as date_time")
            ]);
    }

    /**
     * @param  Driver  $driver
     * @param  $from_date
     * @return Collection
     */
    public function getScheduleOfUnpaidDays($driver, $from_date): Collection
    {
        return $this->driverScheduleContract
            ->where('driver_id', '=', $driver->driver_id)
            ->where('driver_contract_id', '=', $driver->active_contract->driver_contract_id)
            ->where('date', '>=', $driver->last_active_waybill->end_time->format('Y-m-d'))
            ->where('date', '<', $from_date->format('Y-m-d'))
            ->findAll([
                'driver_schedule_id',
                'driver_id',
                'driver_contract_id',
                'working',
                DB::raw("CONCAT(`date`, ' ".$driver->last_active_waybill->end_time->format('H:i:s')."') as date_time")
            ]);
    }

    /**
     * @param  int  $driver_id
     */
    protected function maximumAllowableQuantityWaybills($driver_id)
    {
        $driver = $this->driverContract
            ->with([
                'active_waybills',
                'current_franchise' => fn($query) => $query->with([
                    'option'
                ]),
                'type'
            ])
            ->find($driver_id, ['driver_id', 'current_franchise_id']);

        return $driver->current_franchise->option->waybill_max_days[$driver->type->driver_type_id] - $driver->active_waybills->count();
    }

    /**
     * @param  Driver  $driver
     * @param $current_waybill_price
     * @return array
     */
    #[ArrayShape([
        'debt' => 'float|mixed|null',
        'amount' => 'mixed',
        'repayment_amount' => 'float',
        'repayment_waybill' => '',
        'balance' => 'float',
        'transaction_cash' => 'float'
    ])]
    protected function getDebtCalculate(Driver $driver, $current_waybill_price): array
    {
        $repayment = $this->repaymentContract
            ->where('min_debt', '<=', $driver->cash->debt)
            ->where('max_debt', '>=', $driver->cash->debt)
            ->findFirst(['amount', 'debt_repayment_id']);

        $initial = round($driver->cash->debt / $repayment->amount, 2);

        $this->driverWalletContract->where('driver_id', '=', $driver->driver_id)->updateSet([
            'repayment_id' => $repayment->debt_repayment_id,
            'min_repayment' => $initial
        ]);

        return [
            'debt' => $driver->cash->debt,
            'amount' => $driver->cash->selected_amount ?? $repayment->amount,
            'repayment_amount' => $initial,
            'repayment_waybill' => $current_waybill_price,
            'balance' => $driver->cash->balance ?? 0.0,
            'transaction_cash' => $driver->cash->transaction_cash ?? 0.0,
        ];
    }

    /**
     * @param  int  $driver_id
     * @param $days
     * @return array
     */

    public function selectedWaybillDaysPrice(int $driver_id, $days): array
    {
        $limitActiveWaybillsIsNotReached = $this->checkDriverActiveWaybillsLimit($driver_id, $days);

        if (!$limitActiveWaybillsIsNotReached) {
            return [
                'message' => trans('messages.worker_active_waybills_reached')
            ];
        }
        $driver = $this->driverContract
            ->where('driver_id', '=', $driver_id)
            ->with('last_active_waybill')
            ->find($driver_id, ['driver_id']);

        $schedules = $this->getScheduleFromCurrentDate($driver, now(), $days);
        $prices = [];

        foreach ($schedules as $schedule) {
            $prices[] = [
                'date' => $schedule->date_time,
                'amount' => $schedule->working
                    ? $driver->active_contract->busy_days_price
                    : $driver->active_contract->free_days_price
            ];
        }
        $waybillsPrice = array_sum(array_column($prices, 'amount'));

        return [
            'waybills_price' => $waybillsPrice
        ];
    }

    /**
     * @param  int  $driver_id
     * @param  int  $days
     * @return bool
     */
    public function checkDriverActiveWaybillsLimit($driver_id, $days): bool
    {
        $driver = $this->driverContract
            ->with([
                'active_waybills' => fn($query) => $query->select(['*']),
                'current_franchise' => fn($query) => $query->with(['option']),
                'type' => fn($query) => $query->select(['*'])
            ])
            ->find($driver_id, ['driver_id', 'current_franchise_id']);

        if (!$driver) {
            return false;
        }

        return $driver->active_waybills->count() + $days <= $driver->current_franchise->option->waybill_max_days[$driver->type->driver_type_id];
    }

    /**
     * @param  Driver  $driver
     * @param  $from_date
     * @param  int  $days
     * @return Collection
     */
    protected function getScheduleFromCurrentDate(Driver $driver, $from_date, $days = 1): Collection
    {
        return $this->driverScheduleContract
            ->where('driver_id', '=', $driver->driver_id)
            ->where('driver_contract_id', '=', $driver->active_contract->driver_contract_id)
            ->where('date', '>=', $from_date->format('Y-m-d'))
            ->where('date', '<', $from_date->addDays($days)->format('Y-m-d'))
            ->findAll([
                'driver_schedule_id',
                'driver_id',
                'driver_contract_id',
                'working',
                DB::raw("CONCAT(`date`, ' ".$from_date->format('H:i:s')."') as date_time")
            ]);
    }

    /**
     * @param $driver_id
     * @param $contract
     * @param  int  $days
     * @return float|int|string
     */
    public function getDriverLatestComingWaybill($driver_id, $contract, int $days = 1): float|int|string
    {
        $latest_w = $this->waybillContract
                ->where('driver_id', '=', $driver_id)
                ->firstLatest('number', ['waybill_id', 'number', 'driver_id', 'car_id', 'end_time'])
                ->end_time ?? f_now();

        $d_schedule = $this->driverScheduleContract
            ->where('driver_id', '=', $driver_id)
            ->where('driver_contract_id', '=', $contract['driver_contract_id'])
            ->where('date', '>=', Carbon::parse($latest_w)->format('Y-m-d'))
            ->where('date', '<', now()->addDays($days)->format('Y-m-d'))
            ->findAll(['driver_schedule_id', 'driver_id', 'driver_contract_id', 'working', 'date']);

        $working_days_count = 0;
        $free_days_count = 0;

        foreach ($d_schedule as $schedule) {
            if ($schedule->working) {
                ++$working_days_count;
            } else {
                ++$free_days_count;
            }
        }

        return ($working_days_count * $contract['busy_days_price']) + ($free_days_count * $contract['free_days_price']);
    }

    /**
     * @param $driver_id
     * @param $price
     * @param $type
     * @return array
     */
    public function addedCurrentCash($driver_id, $price, $type): array
    {
        $driver = $this->getDriverData($driver_id);

        $cash = ($driver->cash->transaction_cash ?? 0.0) + $price;
        $this->driverWalletContract->updateOrCreate(['driver_id', '=', $driver_id], ['transaction_cash' => $cash, 'cash_type' => $type]);
        $result = ['cash' => $cash, 'balance' => $driver->cash->balance ?? 0.0];

        switch ($type) {
            case ConstTransactionType::BALANCE():
                return $result;
            case ConstTransactionType::DEBT() && $driver->cash->debt:
                $repay = $driver->cash->min_repayment < $cash;
                return array_merge($result, ['all_debt' => $driver->cash->debt, 'debt' => $driver->cash->min_repayment, 'repay' => $repay]);
            case ConstTransactionType::WAYBILL():
                $waybill_price = $driver->cash->min_repayment_waybill ?? $driver->active_contract->busy_days_price;
                $repay = $waybill_price < $cash;
                return array_merge($result, ['all_debt' => $driver->cash->debt ?? 0.0, 'debt_waybill' => $waybill_price, 'repay' => $repay]);
            default:
                return [];
        }
    }

    /**
     * @param $driver_id
     * @return object|null
     */
    protected function getDriverData($driver_id): ?object
    {
        return $this->driverContract
            ->where('driver_id', '=', $driver_id)
            ->with([
                'active_contract' => fn($query) => $query->select(['driver_id', 'driver_contract_id', 'free_days_price', 'busy_days_price']),
                'cash' => fn($query) => $query->select(['driver_id', 'balance', 'transaction_cash', 'debt', 'repayment_id', 'min_repayment_waybill'])
            ])
            ->findFirst();
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'all_balance' => 'mixed'
    ])]
    public function payBalance($driver_id, $cash): array
    {
        $driver = $this->getDriverCashCarTerminal($driver_id);

        $deposit = $driver->cash->balance + $driver->cash->transaction_cash;
        $amount_paid = $driver->cash->amound_paid + $deposit;

        $this->driverWalletContract->where('driver_id', '=', $driver_id)->updateSet([
            'balance' => $deposit,
            'transaction_cash' => 0,
            'cash_type' => ConstTransactionType::BALANCE()->getValue(),
            'amount_paid' => $amount_paid
        ]);

        $last_transaction = $this->transactionContract
                ->where('side_id', '=', $driver_id)
                ->where('side_type', '=', $this->driverContract->getMap())
                ->firstLatest('created_at', ['franchise_transaction_id', 'side_id', 'side_type', 'remainder'])
                ->remainder ?? 0;

        $this->transactionContract->create([
            'franchise_id' => $driver->current_franchise_id,
            'park_id' => $driver->car->park_id,
            'side_id' => $driver->driver_id,
            'side_type' => $driver->getMap(),
            'reason_id' => $driver->cash->driver_cash_id,
            'reason_type' => $this->driverWalletContract->getMap(),
            'amount' => $cash,
            'side_cost' => $cash,
            'franchise_cost' => 0,
            'remainder' => $last_transaction + $cash,
            'out' => false,
            'payment_type_id' => PaymentType::CASH,
            'type' => ConstTransactionType::BALANCE()->getValue(),
        ]);

        return ['all_balance' => $deposit];
    }

    /**
     * @param $driver_id
     * @return object|null
     */
    protected function getDriverCashCarTerminal($driver_id): ?object
    {
        return $this->driverContract
            ->with([
                'cash' => fn($query) => $query->select(['driver_id', 'transaction_cash', 'balance', 'debt']),
                'terminal' => fn($query) => $query->select(['terminal_id', 'auth_driver_id']),
                'car' => fn($query) => $query->select(['car_id', 'park_id']),
            ])
            ->find($driver_id, ['driver_id', 'car_id', 'current_franchise_id']);
    }

    /**
     * @inheritDoc
     * @throws Lexcept
     * @throws Exception
     */
    public function payWaybill($driver_id, int|float $cash = 0.0, int|float $balance = 0.0, $days = 1): ?array
    {
        $limitActiveWaybills = $this->checkDriverActiveWaybillsLimit($driver_id, $days);

        if (!$limitActiveWaybills) {
            return null;
        }
        $driver = $this->getDriverPayWaybill($driver_id);
        $prices = $this->calculateWaybillsPrices($driver, $days);

        if (session('is_corporate')) {
            $waybills = [];
            foreach ($prices as $price) {
                $driver = $this->generateWaybill($driver, 0, 0, Carbon::parse($price['date']));
                $waybills[] = $driver;
            }

            return compact('waybills');
        }
        $waybills = [];

        $waybills_price = 0;

        if ($cash > 0) {
            $this->artificialAddBalance($driver, $cash);
        }

        foreach ($prices as $price) {
            $waybills_price += $price['amount'];
            if ($driver) {
                $payed = $this->payWaybillPaid($driver, $cash, $balance, $waybills_price);
                $driverGeneratedWaybill_info = $this->generateWaybill($driver, $price['amount'], $payed['balance'], Carbon::parse($price['date']));
                $driverData = $this->getDriverPayWaybill($driver_id);
                $driverData->waybill_number = $driverGeneratedWaybill_info->waybill_number;
                $driverData->transaction_id = $driverGeneratedWaybill_info->transaction_id ?? null;
                $driverData->start_date = $driverGeneratedWaybill_info->start_date;
                $driverData->end_date = $driverGeneratedWaybill_info->end_date;
                $waybills[] = $driverData;
            }
        }
        $payed = $this->payWaybillPaid($driver, $cash, $balance, $waybills_price);

        if (empty($waybills) || !$payed) {
            return null;
        }

        $this->driverWalletContract
            ->where('driver_id', '=', $driver_id)
            ->updateSet(['amount_paid' => $driver->cash->amount_paid + $cash]);

        return compact('payed', 'waybills');
    }

    /**
     * @param $driver_id
     * @return object|null
     */
    protected function getDriverPayWaybill($driver_id): ?object
    {
        return $this->driverContract
            ->with([
                'cash' => fn($query) => $query->select(['driver_wallet_id', 'driver_id', 'transaction_cash', 'balance', 'debt']),
                'terminal' => fn($query) => $query->select(['terminal_id', 'auth_driver_id']),
                'driver_info' => fn($query) => $query->select(['driver_info_id', 'name', 'surname', 'patronymic', 'id_kis_art', 'license_code']),
                'active_contract' => fn($query) => $query->select(['driver_contract_id', 'driver_id', 'busy_days_price', 'free_days_price']),
                'car' => fn($query) => $query
                    ->select([
                        'car_id',
                        'park_id',
                        'current_driver_id',
                        'franchise_id',
                        'mark',
                        'model',
                        'year',
                        'color',
                        'state_license_plate',
                        'speedometer',
                        'garage_number',
                        'year',
                        'vin_code'
                    ]),
                'park' => fn($query) => $query->with([
                    'entity' => fn($query) => $query
                        ->select(['legal_entity_id', 'type_id', 'city_id', 'name', 'zip_code', 'address', 'phone', 'tax_psrn', 'tax_psrn_serial'])
                        ->with([
                            'type' => fn($query) => $query->select(['entity_type_id', 'abbreviation']),
                            'city' => fn($query) => $query->select(['city_id', 'name']),
                        ])
                ]),
                'last_active_waybill',
                'type'
            ])
            ->find($driver_id, ['driver_id', 'driver_info_id', 'current_franchise_id', 'car_id']);
    }

    /**
     * @param  Driver  $driver
     * @param  int  $days
     * @return array
     */
    protected function calculateWaybillsPrices(Driver $driver, int $days = 1): array
    {
        if ($driver->last_active_waybill) {
            if ($driver->last_active_waybill->end_time->isFuture()) {
                $from_date = $driver->last_active_waybill->end_time;
                $schedules = $this->driverScheduleContract
                    ->where('driver_id', '=', $driver->driver_id)
                    ->where('driver_contract_id', '=', $driver->active_contract->driver_contract_id)
                    ->where('date', '>=', $from_date->format('Y-m-d'))
                    ->where('date', '<', $from_date->addDays($days)->format('Y-m-d'))
                    ->findAll([
                        'driver_schedule_id',
                        'driver_id',
                        'driver_contract_id',
                        'working',
                        DB::raw("CONCAT(`date`, ' ".$from_date->format('H:i:s')."') as date_time")
                    ]);
            } else {
                $from_date = now();
                $scheduleOfUnpaidDays = $this->getScheduleOfUnpaidDays($driver, $from_date);
                $scheduleOfUnpaidDays_array = $scheduleOfUnpaidDays->toArray();
                if (!empty($scheduleOfUnpaidDays_array)) {
                    $last_unpaid_day_date_time = $scheduleOfUnpaidDays_array[array_key_last($scheduleOfUnpaidDays_array)]['date_time'];
                    $schedules = $this->driverScheduleContract
                        ->where('driver_id', '=', $driver->driver_id)
                        ->where('driver_contract_id', '=', $driver->active_contract->driver_contract_id)
                        ->where('date', '>=', $last_unpaid_day_date_time)
                        ->where('date', '<=', Carbon::parse($last_unpaid_day_date_time)->addDays($days)->format('Y-m-d'))
                        ->findAll([
                            'driver_schedule_id',
                            'driver_id',
                            'driver_contract_id',
                            'working',
                            DB::raw("CONCAT(`date`, ' ".Carbon::parse($last_unpaid_day_date_time)->format('H:i:s')."') as date_time")
                        ]);
                } else {
                    $last_unpaid_day_date_time = $driver->last_active_waybill->end_time;
                    $schedules = $this->getScheduleUnpaidOfToday($driver, $last_unpaid_day_date_time, $days);
                }
            }
        } else {
            $from_date = now();

            $schedules = $this->getScheduleFromCurrentDate($driver, $from_date, $days);
        }

        $prices = [];

        if (isset($scheduleOfUnpaidDays)) {
            foreach ($scheduleOfUnpaidDays as $schedule) {
                $prices[] = [
                    'date' => $schedule->date_time,
                    'amount' => $schedule->working
                        ? $driver->active_contract->busy_days_price
                        : $driver->active_contract->free_days_price
                ];
            }
        }

        foreach ($schedules as $schedule) {
            $prices[] = [
                'date' => $schedule->date_time,
                'amount' => $schedule->working
                    ? $driver->active_contract->busy_days_price
                    : $driver->active_contract->free_days_price
            ];
        }

        return $prices;
    }

    /**
     * @param  Driver  $driver
     * @param $amount
     * @param  string|float|int  $remainder
     * @param  Carbon|null  $from_date
     * @param  int  $days
     * @param  bool  $checked
     * @param  bool  $manual
     * @return Driver{waybill_number: int|string, transaction_id: int|string, starte_date: string|Carbon, end_date: string|Carbon}
     * @throws Lexcept
     */
    protected function generateWaybill(
        Driver $driver,
        $amount,
        string|float|int $remainder = 0,
        Carbon $from_date = null,
        int $days = 1,
        bool $checked = false,
        bool $manual = false
    ): Driver {
        $start_date = $from_date ? $from_date->format('Y-m-d H:i') : now()->format('Y-m-d H:i');
        $end_date = $from_date ? $from_date->addDays($days)->format('Y-m-d H:i') : now()->addDays($days)->format('Y-m-d H:i');
        $terminal = $this->terminalContract->where('auth_driver_id', '=', $driver->driver_id)->findFirst(['terminal_id', 'auth_driver_id']);

        $waybill = $this->waybillContract->create([
            'driver_id' => $driver->driver_id,
            'terminal_id' => $terminal->terminal_id ?? null,
            'car_id' => $driver->car_id,
            'start_time' => $start_date,
            'end_time' => $end_date,
            'verified' => $checked,
            'signed' => $checked,
            'price' => $amount
        ]);

        if (!$waybill) {
            throw new Lexcept('Server error Waybill create', 500);
        }

        if ($manual) {
            $data = [
                'franchise_id' => $driver->current_franchise_id,
                'park_id' => $driver->car->park_id,
                'side_id' => $driver->{$driver->getKeyName()},
                'side_type' => $driver->getMap(),
                'reason_id' => $waybill->{$waybill->getKeyName()},
                'reason_type' => $this->waybillContract->getMap(),
                'type' => ConstTransactionType::WAYBILL()->getValue(),
                'franchise_cost' => $amount,
                'amount' => $amount,
                'remainder' => $remainder,
                'out' => false,
                'payed' => true,
                'payment_type_id' => PaymentType::CASH,
            ];
        } else {
            $data = [
                'franchise_id' => $driver->current_franchise_id,
                'park_id' => $driver->car->park_id,
                'side_id' => $driver->driver_id,
                'side_type' => $driver->getMap(),
                'reason_id' => $waybill->{$waybill->getKeyName()},
                'reason_type' => $this->waybillContract->getMap(),
                'type' => ConstTransactionType::WAYBILL()->getValue(),
                'franchise_cost' => $amount,
                'amount' => $amount,
                'out' => false,
                'payed' => true,
                'payment_type_id' => PaymentType::CASH,
                'remainder' => $remainder,
            ];
        }

        if ($amount > 0) {
            $transaction_id = $this->transactionContract->create($data)->{$this->transactionContract->getKeyName()} ?? null;

            if (!$transaction_id) {
                throw new Lexcept('Server error transaction create', 500);
            }
        }

        $driver->waybill_number = $waybill->number;
        $driver->transaction_id = $transaction_id ?? null;
        $driver->start_date = $start_date;
        $driver->end_date = $end_date;

        return $driver;
    }

    /**
     * @param  Driver  $driver
     * @param $amount
     */
    protected function artificialAddBalance(Driver $driver, $amount): void
    {
        $last_remainder = $this->transactionContract
                ->where('side_id', '=', $driver->driver_id)
                ->where('side_type', '=', $this->driverContract->getMap())
                ->latest('created_at')
                ->first(['side_id', 'side_type', 'remainder', 'created_at'])
                ->remainder ?? 0;

        $this->transactionContract->create([
            'franchise_id' => $driver->current_franchise_id,
            'park_id' => $driver->car->park_id,
            'payment_type_id' => PaymentType::getTypeId(PaymentType::CASH),
            'side_id' => $driver->driver_id,
            'side_type' => $driver->getMap(),
            'reason_id' => $driver->cash->driver_wallet_id,
            'reason_type' => $driver->cash->getMap(),
            'type' => ConstTransactionType::BALANCE()->getValue(),
            'franchise_cost' => 0,
            'side_cost' => $amount,
            'remainder' => $last_remainder + $amount,
            'amount' => $amount,
            'out' => false,
            'payed' => true,
            'comment' => ''
        ]);
    }

    /**
     * @param  Driver  $driver
     * @param  int|float  $cash
     * @param  int|float  $balance
     * @return array{debt_repayment: int|float, debt_left: int|float, balance: int|float}
     * @throws Lexcept
     */
    #[ArrayShape([
        'debt_payed' => 'float',
        'debt_left' => 'float|mixed',
        'balance' => 'float|int'
    ])]
    protected function payWaybillPaid(Driver $driver, int|float $cash, int|float $balance, $price): array
    {
        if (!$driver->active_contract) {
            throw new Lexcept('Driver is not active contract', 420);
        }

        if ($driver->cash->balance == $balance) {
            $new_balance = ($balance + $cash) - $price;
        } elseif ($cash > 0 && $driver->cash->balance != $balance) {
            $current_balance = $driver->cash->balance - $balance;
            $new_balance = ($current_balance + $cash) - $price;
        } else {
            $new_balance = $driver->cash->balance - $price;
        }

        $this->driverWalletContract
            ->where('driver_id', '=', $driver->driver_id)
            ->updateSet([
                'balance' => $new_balance,
                'transaction_cash' => 0,
                'min_repayment_waybill' => 0,
            ]);

        return ['debt_payed' => 0.0, 'debt_left' => $driver->cash->debt ?? 0.0, 'balance' => $new_balance];
    }

    /**
     * @param  int  $driver_id
     * @param  int  $days
     * @param  bool  $checked
     * @return array
     * @throws Lexcept
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function createManualWaybill(int $driver_id, int $days, bool $checked = false): array
    {
        $limitActiveWaybills = $this->checkDriverActiveWaybillsLimit($driver_id, $days);

        if (!$limitActiveWaybills) {
            return [
                'success' => false,
                'message' => trans('messages.worker_active_waybills_reached')
            ];
        }

        $driver = $this->driverContract
            ->with([
                'driver_info',
                'car',
                'cash' => fn($query) => $query->select(['driver_id', 'transaction_cash', 'balance', 'debt']),
                'active_contract',
                'type',
                'last_active_waybill',
                'entity',
                'park'
            ])
            ->find($driver_id, ['driver_id', 'driver_info_id', 'current_franchise_id', 'car_id']);

        $prices = $this->calculateWaybillsPrices($driver, $days);
        $total_price = array_sum(array_column($prices, 'amount'));

        $balance = $driver->cash->balance;

        $debt = $driver->cash->debt;
        $current_debt = $balance - $debt;
        $balance -= $total_price;

        if ($current_debt < 0) {
            return [
                'success' => false,
                'message' => 'Ваш долг '.abs($current_debt).' руб.'
            ];
        }
        if ($balance < 0) {
            return [
                'success' => false,
                'message' => 'Не хватает средств на балансе водителя'
            ];
        }

        $this->driverWalletContract
            ->where('driver_id', '=', $driver->driver_id)
            ->updateSet(['balance' => $balance]);

        $waybills = [];

        $waybills_price = 0;
        foreach ($prices as $price) {
            $waybills_price += $price['amount'];
            $payed = $this->payWaybillPaid($driver, 0, $balance + $total_price, $waybills_price);
            $driver = $this->generateWaybill($driver, $price['amount'], $payed['balance'], Carbon::parse($price['date']), 1, $checked, true);
            $waybills[] = $this->waybillContract
                ->where('number', '=', $driver->waybill_number)
                ->findFirst();
        }
        $this->generateWaybillScan($driver, $waybills);

        return [
            'success' => true,
            'message' => 'Путевый лист успешно создан'
        ];
    }

    /**
     * @param  Driver  $driver
     * @param  array  $waybills
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    private function generateWaybillScan(Driver $driver, array $waybills): void
    {
        $path = storage_path('drivers'.DS.'waybills'.DS);
        $main_file = $path.'Main.xls';

        $spreadsheet = IOFactory::load($main_file);

        foreach ($waybills as $waybill) {
            $file_name = md5(microtime()).'.xlsx';

            $waybill_middle_time = $waybill->start_time->addHours(12);

            $spreadsheet_copy = clone $spreadsheet;

            $sheet = $spreadsheet_copy->getActiveSheet();

            $this->setSheetCellsValue($sheet, ['BI3', 'BI70'], $waybill->number);
            $this->setSheetCellsValue(
                $sheet,
                ['O7', 'O74'],
                $driver->park->entity->full_title
            );
            $this->setSheetCellsValue($sheet, ['T10', 'T77'], $driver->car->model.' '.$driver->car->mark);
            $this->setSheetCellsValue($sheet, ['R11', 'R78'], $driver->car->vin_code);
            $this->setSheetCellsValue($sheet, ['AD13', 'AD80'], $driver->car->state_license_plate);
            $this->setSheetCellsValue($sheet, ['L14', 'L81'], $driver->driver_info->full_name);
            $this->setSheetCellsValue($sheet, ['DH11', 'DH78'], $driver->driver_info->id_kis_art);
            $this->setSheetCellsValue($sheet, ['DH13', 'DH80'], $driver->car->garage_number);
            $this->setSheetCellsValue($sheet, ['T17', 'T84'], $driver->driver_info->license_code);

            $sheet->setCellValue('B22', $waybill->start_time->format('H:i'));
            $sheet->setCellValue('Z22', $waybill_middle_time->format('H:i'));

            $sheet->setCellValue('B89', $waybill_middle_time->format('H:i'));
            $sheet->setCellValue('Z89', $waybill->end_time->format('H:i'));

            $this->setSheetCellsValue($sheet, ['DG28', 'DG95'], $driver->driver_info->full_name_short);
            $this->setSheetCellsValue($sheet, ['V35', 'V102'], $driver->car->speedometer);
            $this->setSheetCellsValue($sheet, ['HK57', 'HK124'], $driver->driver_info->full_name_short);

            $sheet->setCellValue('C6', 'с '.$waybill->start_time->format('d.m.Y').' по '.$waybill_middle_time->format('d.m.Y'));
            $sheet->setCellValue('DY133', 'Учтен: '.$waybill->start_time->format('d.m.Y H:i:s'));

            $sheet->setCellValue('C73', 'с '.$waybill_middle_time->format('d.m.Y').' по '.$waybill->end_time->format('d.m.Y'));
            $sheet->setCellValue('DY66', 'Учтен: '.$waybill_middle_time->format('d.m.Y H:i:s'));


            $qr_image = qr_generate($waybill->number, 110, 110);
            $qr_image_name = md5(microtime()).'.png';

            Storage::disk('local')->put('tmp/'.$qr_image_name, $qr_image);

            $qr_image_path = Storage::disk('local')->path('tmp/'.$qr_image_name);

            $drawing_qr_1 = new Drawing();
            $drawing_qr_1->setPath($qr_image_path);
            $drawing_qr_1->setCoordinates('EC58');
            $drawing_qr_1->setWorksheet($spreadsheet_copy->getActiveSheet());

            $drawing_qr_2 = new Drawing();
            $drawing_qr_2->setPath($qr_image_path);
            $drawing_qr_2->setCoordinates('EC125');
            $drawing_qr_2->setWorksheet($spreadsheet_copy->getActiveSheet());


            $writer = new Xlsx($spreadsheet_copy);
            $writer->save($path.$file_name);

            Storage::disk('local')->delete('tmp/'.$qr_image_name);

            $this->waybillContract->update($waybill->{$waybill->getKeyName()}, ['waybill' => 'storage/drivers/waybills/'.$file_name]);
        }
    }

    /**
     * @param  int  $waybill_id
     * @param  bool  $checked
     * @return bool
     * @throws Lexcept
     */
    public function isToggleCheckedWaybill(int $waybill_id, bool $checked = false): bool
    {
        $waybill = $this->waybillContract->find($waybill_id, ['waybill_id', 'driver_id']);

        if (!$waybill || !$this->waybillContract->update($waybill_id, ['verified' => $checked, 'signed' => $checked])) {
            throw new Lexcept('Error with toggle waybill', 500);
        }

        $driver = $this->driverContract->find($waybill->driver_id, ['driver_id', 'phone', 'current_franchise_id', 'car_id']);
        WaybillAnnulled::broadcast($driver, ['message' => 'waybill '.$checked ? trans('messages.waybill_activated') : trans('messages.waybill_deactivated')]);

        return true;
    }

    /**
     * @param  int  $driver_id
     */
    public function restoreCurrentWaybill(int $driver_id): void
    {
        $driver = $this->driverContract
            ->with('current_waybill')
            ->find($driver_id, ['driver_id', 'driver_info_id', 'current_franchise_id', 'car_id']);

        $this->workerService->annulWaybill($driver->current_waybill->waybill_id);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    #[ArrayShape([
        'debt_payed' => "\|\float|mixed|null",
        'debt_left' => "\float|mixed|null|mixed",
        'balance' => 'float'
    ])]
    public function payDebt($driver_id, $cash, $deposit): ?array
    {
        $driver = $this->getDriverCashCarTerminal($driver_id);
        $debt = $cash + $deposit;

        $this->updateDebtsTable($driver_id, $debt);

        if ($driver->cash->debt > $debt) {
            $result = $this->driverDebtGreater($driver, $debt, $cash, $deposit);
        } else {
            $result = $this->driverDebtSmaller($driver, $cash, $deposit);
        }

        $last_remainder = $this->transactionContract
                ->where('side_id', '=', $driver_id)
                ->where('side_type', '=', $this->driverContract->getMap())
                ->firstLatest('created_at', ['franchise_transaction_id', 'side_id', 'side_type', 'remainder'])
                ->remainder ?? 0;

        $this->transactionContract->create([
            'amount' => $cash + $deposit,
            'franchise_id' => $driver->current_franchise_id,
            'park_id' => $driver->car->park_id,
            'side_id' => $driver_id,
            'side_type' => $driver->getMap(),
            'type' => ConstTransactionType::DEBT()->getValue(),
            'out' => false,
            'payed' => true,
            'payment_type_id' => PaymentType::CASH,
            'franchise_cost' => $cash,
            'remainder' => $last_remainder
        ]);
        $this->driverWalletContract->where('driver_id', '=', $driver_id)->updateSet(['amount_paid' => $driver->cash->amount_paid + $cash]);

        return $result;
    }

    /**
     * @param $driver_id
     * @param $fullCash
     */
    protected function updateDebtsTable($driver_id, $fullCash)
    {
        $query = $this->debtContract
            ->where('debtor_id', '=', $driver_id)
            ->where('firm_paid', '=', 0);

        $lastDebt = $query->orderBy('debt_id', 'desc')->findFirst();

        if ($lastDebt) {
            $fullCash = sprintf('%.2f', $fullCash);
            $debtCost = $lastDebt['cost'];
            $debtCost_paid = $lastDebt['cost_paid'];
            $debt_id = $lastDebt['debt_id'];

            if ($debtCost_paid > 0) {
                if (bcadd($fullCash, $debtCost_paid, 2) >= $debtCost) {
                    $current_cash = bcadd($fullCash, $debtCost_paid, 2) - $debtCost;
                } else {
                    $current_cash = 0;
                }
            } elseif ($fullCash > $debtCost) {
                $current_cash = bcsub($fullCash, $debtCost, 2);
            } else {
                $current_cash = 0;
            }

            if ($current_cash > 0) {
                $query->where('debt_id', '=', $debt_id)
                    ->updateSet(['cost_paid' => $debtCost, 'firm_paid' => 1]);

                $this->updateDebtsTable($driver_id, $current_cash);
            } elseif (bcadd($fullCash, $debtCost_paid, 2) === $debtCost) {
                $query->where('debt_id', '=', $debt_id)
                    ->updateSet(['cost_paid' => bcadd($debtCost_paid, $fullCash, 2), 'firm_paid' => 1]);
            } else {
                $query->where('debt_id', '=', $debt_id)
                    ->updateSet(['cost_paid' => bcadd($debtCost_paid, $fullCash, 2)]);
            }
        }
    }

    /**
     * @param  Driver  $driver
     * @param $debt
     * @param $cash
     * @param $deposit
     * @return array
     */
    #[ArrayShape([
        'debt_payed' => '',
        'debt_left' => 'float|mixed|null',
        'balance' => 'float'
    ])]
    protected function driverDebtGreater(Driver $driver, $debt, $cash, $deposit): array
    {
        $new_debt = $driver->cash->debt - $debt;
        $this->driverWalletContract
            ->where('driver_id', '=', $driver->driver_id)
            ->updateSet(['debt' => $new_debt, 'maturity' => ++$driver->cash->maturity, 'transaction_cash' => ($driver->cash->transaction_cash - $cash)]);
        $deposit
            ? $this->driverWalletContract
            ->where('driver_id', '=', $driver->driver_id)
            ->updateSet(['balance' => $driver->cash->balance - $deposit])
            : null;
        $left_debt = $new_debt;

        if ($driver->cash->debt < $driver->cash->min_repayment) {
            $this->driverWalletContract
                ->where('driver_id', '=', $driver->driver_id)
                ->updateSet(['min_repayment' => $driver->cash->debt]);
        }

        return ['debt_payed' => $debt, 'debt_left' => $left_debt, 'balance' => $driver->cash->balance];
    }

    /**
     * @param  Driver  $driver
     * @param $cash
     * @param $deposit
     * @return array
     */
    #[ArrayShape([
        'debt_payed' => 'float|mixed|null',
        'debt_left' => 'mixed',
        'balance' => 'float'
    ])]
    protected function driverDebtSmaller(Driver $driver, $cash, $deposit): array
    {
        $new_deposit = 0.0;
        $all_paid = $cash + $deposit;

        if ($all_paid < $driver->cash->debt) {
            $new_deposit = $all_paid - $driver->cash->debt;
        }

        if ($deposit) {
            $new_deposit += $deposit;
        }

        $payed_debt = $all_paid - $new_deposit;
        $debt = $driver->cash->debt - $payed_debt;

        if ($payed_debt > $debt) {
            $deposit = 0.1;
            $new_deposit -= $payed_debt - $debt;
        }

        if ($cash > $driver->cash->debt) {
            $new_deposit = $cash - $driver->cash->debt;
        }

        $debt = ($driver->cash->debt) - ($driver->cash->debt % $all_paid);

        $this->driverWalletContract->where('driver_id', '=', $driver->driver_id)->updateSet([
            'balance' => $deposit ? $driver->cash->balance - $deposit : $driver->cash->balance + $new_deposit,
            'transaction_cash' => ($driver->cash->transaction_cash - $cash),
            'debt' => $debt % $driver->cash->debt,
            'maturity' => ++$driver->cash->maturity
        ]);

        if ($driver->cash->debt < $driver->cash->min_repayment) {
            $this->driverWalletContract->where('driver_id', '=', $driver->driver_id)->updateSet(['min_repayment' => $driver->cash->debt]);
        }

        return ['debt_payed' => $payed_debt, 'debt_left' => $debt, 'balance' => $driver->cash->balance];
    }

    /**
     * @inheritDoc
     * @throws Lexcept
     */
    public function uploadWaybill($transaction_id, $waybill): string
    {
        $path = storage_path('drivers'.DS.'waybills'.DS);
        $file_name = $this->fileUpload($waybill, $path);

        $waybill_data = $this->waybillContract
            ->with(['transaction' => fn($query) => $query->where('franchise_transaction_id', '=', $transaction_id)])
            ->findFirst(['waybill_id', 'waybill']);

        if (!$waybill_data) {
            throw new Lexcept('Failed transaction id', 500);
        }

        $waybill_data->waybill ? $this->deleteOldFile($waybill_data->waybill) : null;

        $this->waybillContract
            ->whereHas('transaction', fn(Builder $query) => $query->where('franchise_transaction_id', '=', $transaction_id))
            ->updateSet(['waybill' => 'storage/drivers/waybills'.$file_name]);

        return "$path$file_name";
    }
}
