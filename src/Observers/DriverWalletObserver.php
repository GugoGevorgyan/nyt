<?php

declare(strict_types=1);

namespace Src\Observers;

use Illuminate\Database\Eloquent\Model;
use Src\Core\Contracts\BaseObserver;
use Src\Models\Driver\DriverWallet;
use Src\Repositories\DebtRepayment\DebtRepaymentContract;
use Src\Repositories\DriverWallet\DriverWalletContract;

/**
 *
 */
class DriverWalletObserver extends BaseObserver
{
    /**
     * @var DriverWalletContract
     */
    protected DriverWalletContract $driverWalletContract;
    /**
     * @var DebtRepaymentContract
     */
    protected DebtRepaymentContract $repaymentContract;

    /**
     * @param  DebtRepaymentContract  $debtRepaymentContract
     * @param  DriverWalletContract  $driverWalletContract
     */
    public function __construct(DebtRepaymentContract $debtRepaymentContract, DriverWalletContract $driverWalletContract)
    {
        $this->repaymentContract = $debtRepaymentContract;
        $this->driverWalletContract = $driverWalletContract;
    }

    /**
     * @param  Model  $driverWallet
     */
    public function saved(Model $driverWallet): void
    {
        $this->debtRepayment($driverWallet);
    }

    /**
     * @param  DriverWallet|Model  $driverWallet
     */
    protected function debtRepayment(DriverWallet $driverWallet): void
    {
        if ($driverWallet->isDirty('debt')) {
            $repayment = $this->repaymentContract
                ->where('min_debt', '<=', $driverWallet->debt)
                ->where('max_debt', '>=', $driverWallet->debt)
                ->findFirst();

            if ($repayment) {
                $this->driverWalletContract->update($driverWallet->driver_wallet_id, ['repayment_id' => $repayment->debt_repayment_id]);
            }
        }
    }

    /**
     * @param  Model  $driverWallet
     */
    public function saving(Model $driverWallet): void
    {
    }

    /**
     * @param  Model  $driverWallet
     */
    public function created(Model $driverWallet): void
    {
        $this->debtRepayment($driverWallet);
    }

    /**
     * @param  Model  $driverWallet
     */
    public function creating(Model $driverWallet): void
    {
    }

    /**
     * @param  Model  $driverWallet
     */
    public function updated(Model $driverWallet): void
    {
        $this->debtRepayment($driverWallet);
    }

    /**
     * @param  Model  $driverWallet
     */
    public function updating(Model $driverWallet): void
    {
    }

    /**
     * @param  Model  $driverWallet
     */
    public function deleted(Model $driverWallet): void
    {
        //
    }

    /**
     * @param  Model  $driverWallet
     */
    public function deleting(Model $driverWallet): void
    {
    }
}
