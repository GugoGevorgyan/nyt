<?php

declare(strict_types=1);

namespace Src\Models\Driver;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Franchise\FranchiseTransaction;
use Src\Models\Terminal\DebtRepayment;

/**
 * Class DriverWallet
 *
 * @package Src\Models\Driver
 * @property int $driver_cash_id
 * @property int $driver_id
 * @property int $cash_type
 * @property float $transaction_cash
 * @property float $balance
 * @property float $deposit
 * @property float $amount_paid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Driver $driver
 * @property mixed debt
 * @property mixed min_repayment
 * @property mixed maturity
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|DriverWallet newModelQuery()
 * @method static Builder|DriverWallet newQuery()
 * @method static Builder|DriverWallet query()
 * @method static Builder|DriverWallet whereAmountPaid($value)
 * @method static Builder|DriverWallet whereBalance($value)
 * @method static Builder|DriverWallet whereCashType($value)
 * @method static Builder|DriverWallet whereCreatedAt($value)
 * @method static Builder|DriverWallet whereDeposit($value)
 * @method static Builder|DriverWallet whereDriverCashId($value)
 * @method static Builder|DriverWallet whereDriverId($value)
 * @method static Builder|DriverWallet whereTransactionCash($value)
 * @method static Builder|DriverWallet whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $driver_wallet_id
 * @property int|null $repayment_id
 * @property string|null $min_repayment_waybill
 * @property-read DebtRepayment|null $repayment
 * @property-read FranchiseTransaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|DriverWallet whereDebt($value)
 * @method static Builder|DriverWallet whereDriverWalletId($value)
 * @method static Builder|DriverWallet whereMaturity($value)
 * @method static Builder|DriverWallet whereMinRepayment($value)
 * @method static Builder|DriverWallet whereMinRepaymentWaybill($value)
 * @method static Builder|DriverWallet whereRepaymentId($value)
 * @property string|null $min_repayment
 * @property float|null $debt
 * @property int|null $maturity
 * @property int $is_paid
 * @method static Builder|DriverWallet whereIsPaid($value)
 */
class DriverWallet extends ServiceModel
{


    /**
     * @var string
     */
    protected $table = 'drivers_wallet';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_wallet_id';
    /**
     * @var string[]
     */
    protected $fillable = ['driver_id', 'transaction_cash', 'balance', 'amount_paid', 'cash_type', 'debt', 'repayment_id', 'min_repayment_waybill','is_paid'];
    /**
     * @var string[]
     */
    protected $casts = ['transaction_cash' => 'float', 'balance' => 'float', 'deposit' => 'float', 'amount_paid' => 'float', 'debt' => 'float'];
    /**
     * @var string
     */
    protected string $map = 'driver_wallet';

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    /**
     * @return MorphOne
     */
    public function transaction(): MorphOne
    {
        return $this->morphOne(FranchiseTransaction::class, 'reason', 'reason_type', 'reason_id');
    }

    /**
     * @return BelongsTo
     */
    public function repayment(): BelongsTo
    {
        return $this->belongsTo(DebtRepayment::class, 'repayment_id', 'debt_repayment_id');
    }
}
