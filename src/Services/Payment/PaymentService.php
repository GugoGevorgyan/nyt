<?php

declare(strict_types=1);


namespace Src\Services\Payment;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;
use ServiceEntity\BaseService;
use Src\Broadcasting\Broadcast\Driver\CarCrashInfo;
use Src\Core\Complex\Acquiring;
use Src\Core\Enums\ConstTransactionType;
use Src\Models\Order\PaymentType;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverType\DriverTypeContract;
use Src\Repositories\DriverWallet\DriverWalletContract;
use Src\Repositories\FranchiseTransaction\FranchiseTransactionContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCorporate\OrderCorporateContract;
use Src\Repositories\PaymentType\PaymentTypeContract;


/**
 * Class PaymentService
 * @package Src\Services\Payment
 */
final class PaymentService extends BaseService implements PaymentServiceContract
{
    use PaymentTrait;

    /**
     * @var string
     */
    protected string $apiKey = '';

    /**
     * PaymentService constructor.
     * @param  PaymentTypeContract  $paymentTypeContract
     * @param  DriverContract  $driverContract
     * @param  DriverWalletContract  $driverWalletContract
     * @param  FranchiseTransactionContract  $transactionContract
     * @param  OrderContract  $orderContract
     * @param  OrderCorporateContract  $orderCorporateContract
     * @param  DriverTypeContract  $driverTypeContract
     */
    public function __construct(
        protected PaymentTypeContract $paymentTypeContract,
        protected DriverContract $driverContract,
        protected DriverWalletContract $driverWalletContract,
        protected FranchiseTransactionContract $transactionContract,
        protected OrderContract $orderContract,
        protected OrderCorporateContract $orderCorporateContract,
        protected DriverTypeContract $driverTypeContract
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getPaymentTypes(array $values = []): ?Collection
    {
        return $this->paymentTypeContract->findAll(['*']) ?: null;
    }

    /**
     * @param  array  $card_data
     *
     * @return array [type]
     * @throws ReflectionException
     */
    public function addCardForClient(array $card_data): array
    {
        [$card_exp_year, $card_exp_month] = explode('/', $card_data['cc_expiration']);

        return $this->recurringStart($card_data['cc_number'], $card_exp_month, $card_exp_year, $card_data['cc_cvc'], 0.1);
    }

    /**
     * @param  string  $pan
     * @param  string  $exp_month
     * @param  string  $exp_year
     * @param  int  $cvc2
     * @param  float  $amount
     *
     * @return array
     * @throws ReflectionException
     */
    public function recurringStart(string $pan, string $exp_month, string $exp_year, int $cvc2, float $amount): array
    {
        $currency = 'RUB';
        $recur_exp = Carbon::create('20'.$exp_year, $exp_month, 1, 0, 0, 0, 'UTC')->format('Ymt');
        $recur_freq = 0;

        $params = compact('pan', 'exp_month', 'exp_year', 'cvc2', 'amount', 'currency', 'recur_exp', 'recur_freq');

        return Acquiring::complex('POST', 'recurring/start', $params);
    }

    /**
     * @param  string  $response_id
     * @param  string  $response_status
     * @param  string  $response_sign
     *
     * @return bool
     */
    public function isResponseSign(string $response_id, string $response_status, string $response_sign): bool
    {
        return bcrypt($response_id.$response_status.$this->apiKey) === $response_sign;
    }

    /**
     * @param  string  $recur_id
     * @param  float  $amount
     *
     * @return array
     * @throws ReflectionException
     */
    public function recurringPayment(string $recur_id, float $amount): array
    {
        $currency = 'RUB';

        $params = compact('amount', 'currency', 'recur_id');

        return Acquiring::complex('POST', 'recurring/payment', $params);
    }

    /**
     * @param  string  $order_id
     * @param  float  $amount
     * @param  string  $reason
     *
     * @return array
     */
    public function refund(string $order_id, float $amount, string $reason): array
    {
        $params = compact('order_id', 'amount', 'reason');

        return $this->acquiring->request('POST', 'refund', $params);
    }

    /**
     * @param  int  $driver_id
     * @param  float  $sum
     * @param  bool  $driver_fault
     * @param  object  $crash
     * @return void
     */
    public function carCrashCostDistributor($driver_id, $sum, bool $driver_fault, object $crash): void
    {
        $driver = $this->driverContract
            ->with(['car' => fn($query) => $query->select('car_id', 'park_id'), 'cash' => fn($query) => $query->select(['driver_id', 'balance', 'debt'])])
            ->find($driver_id, ['driver_id', 'car_id', 'current_franchise_id', 'phone']);

        if (!$driver_fault) {
            return;
        }

        $wallet = $this->driverWalletContract->where('driver_id', '=', $driver_id)->findFirst(['driver_id', 'driver_wallet_id', 'debt']);
        $this->driverWalletContract->where('driver_id', '=', $driver_id)->updateSet(['debt' => $wallet->debt + $sum]);
        $payload = ['new debt' => $sum, 'all' => $wallet->debt + $sum];

        $franchise_cost = $crash->act_sum;

        $last_remainder = $this->transactionContract
                ->where('side_id', '=', $driver->driver_id)
                ->where('side_type', '=', $this->driverContract->getMap())
                ->latest('created_at')
                ->first(['side_id', 'side_type', 'remainder', 'created_at'])
                ->remainder ?? 0;

        $this->transactionContract->create([
            'franchise_id' => $driver->current_franchise_id,
            'park_id' => $driver->car->park_id,
            'worker_id' => get_user_id(),
            'side_id' => $driver->driver_id,
            'side_type' => $driver->getMap(),
            'reason_id' => $crash->{$crash->getKeyName()},
            'reason_type' => $crash->getMap(),
            'payed' => false,
            'out' => $driver_fault,
            'amount' => $crash->act_sum,
            'franchise_cost' => $franchise_cost,
            'type' => ConstTransactionType::CRASH()->getValue(),
            'payment_type_id' => PaymentType::CASH,
            'remainder' => $last_remainder - $sum,
        ]);

        CarCrashInfo::broadcast($driver, $payload);
    }

    /**
     * @param $order_id
     * @param $driver_id
     * @param $price
     * @param $payment_type
     * @return bool|array
     * @throws Exception
     */
    #[ArrayShape([
        'price' => 'float|int',
        'balance' => 'float|int|mixed',
        'debt' => 'float|mixed'
    ])]
    public function driverPercent($order_id, $driver_id, $price, $payment_type): bool|array
    {
        $_payload = $this->orderCalcReCalcPayload($order_id, $driver_id);

        $balance = $_payload['driver']['cash']['balance'] ?? 0;
        $debt = $_payload['driver']['cash']['debt'] ?? 0;

        $driver_price = $price * $_payload['percent'] / 100;
        $driver_debt = $price - $driver_price;

        $this->transactionContract->beginTransaction();

        if ($payment_type === PaymentType::getTypeId(PaymentType::CASH)) {
            if (!$this->driverWalletContract->updateOrCreate(['driver_id', '=', $driver_id], ['debt' => ($debt + $driver_debt)])) {
                $this->transactionContract->rollBack();
                return false;
            }

            $remainder = true;
        } else {
            if (!$this->driverWalletContract->updateOrCreate(['driver_id', '=', $driver_id], ['balance' => ($driver_price + $balance)])) {
                $this->transactionContract->rollBack();
                return false;
            }

            $remainder = false;
        }

        $create_transaction = $this->transactionContract->create([
            'franchise_id' => $_payload['driver']['current_franchise_id'],
            'park_id' => $_payload['driver']['car->park_id'],
            'side_id' => $_payload['driver']['driver_id'],
            'side_type' => $_payload['driver']->getMap(),
            'second_side_id' => $_payload['second_side'] ? $_payload['second_side']['company']['company_id'] : null,
            'second_side_type' => $_payload['second_side'] && $_payload['second_side']['company'] ? $_payload['second_side']['company']->getMap() : null,
            'reason_id' => $order_id,
            'reason_type' => $this->orderContract->getMap(),
            'type' => ConstTransactionType::ORDER()->getValue(),
            'payment_type_id' => PaymentType::CASH,

            'remainder' => $remainder ? (($_payload['last_transaction']['remainder'] ?? 0) - $driver_debt) : ($driver_price + ($_payload['last_transaction']['remainder'] ?? 0.0)),
            'franchise_cost' => $driver_debt,
            'side_cost' => $payment_type === PaymentType::getTypeId(PaymentType::CASH) ? ($price - $driver_debt) : $driver_price,
            'amount' => $price,

            'out' => false,
            'payed' => false,
        ]);

        if (!$create_transaction) {
            $this->transactionContract->rollBack();
            return false;
        }

        $this->transactionContract->commit();

        $driver_debt = $driver_debt ?? null;

        return [
            'price' => $driver_price,
            'balance' => $_payload['driver']['cash']['balance'] + $driver_price,
            'debt' => $_payload['driver']['debt']['debt'] ?? 0.0 + ($driver_debt ?? 0.0)
        ];
    }

    /**
     * @param $order_id
     * @param $driver_id
     * @param $price
     * @param $payment_type
     * @return void
     * @throws Exception
     */
    public function workerOrderReCalc($order_id, $driver_id, $price, $payment_type): void
    {
        $_payload = $this->orderCalcReCalcPayload($order_id, $driver_id);

        $driver_price = $_payload['last_transaction']['side_cost'] ?? $_payload['driver']['cash']['balance'] ?? 0;
        $driver_debt = $_payload['driver']['cash']['debt'] ?? 0;
        $franchise_cost = $_payload['last_franchise_transaction']['franchise_cost'];
        $remainder = $_payload['last_transaction']['remainder'];

        if ($price > $_payload['last_order_transaction']['amount']) {
            $franchise_cost += $price - $_payload['last_order_transaction']['amount'];
        }

        if ($price < $_payload['last_order_transaction']['amount']) {
            $franchise_cost -= ($_payload['last_order_transaction']['amount'] - $price) * (100 - $_payload['percent']) / 100;
            $driver_price -= ($_payload['last_order_transaction']['amount'] - $price) * $_payload['percent'] / 100;
            $driver_debt -= ($_payload['last_order_transaction']['amount'] - $price) * (100 - $_payload['percent']) / 100;
            $remainder += ($_payload['last_order_transaction']['amount'] - $price) * (100 - $_payload['percent']) / 100;
        }

        $this->transactionContract->beginTransaction(function () use (
            $payment_type,
            $driver_id,
            $order_id,
            $driver_debt,
            $driver_price,
            $_payload,
            $franchise_cost,
            $price,
            $remainder
        ) {
            if ($price < $_payload['last_order_transaction']['amount']) {
                if ($payment_type === PaymentType::getTypeId(PaymentType::CASH)) {
                    $this->driverWalletContract->updateOrCreate(['driver_id', '=', $driver_id], ['debt' => $driver_debt]);
                } else {
                    $this->driverWalletContract->updateOrCreate(['driver_id', '=', $driver_id], ['balance' => $driver_price]);
                }
            }

            $this->transactionContract->create([
                'franchise_id' => $_payload['driver']['current_franchise_id'],
                'park_id' => $_payload['driver']['car->park_id'],
                'side_id' => $_payload['driver']['driver_id'],
                'side_type' => $_payload['driver']->getMap(),
                'second_side_id' => $_payload['second_side'] ? $_payload['second_side']['company']['company_id'] : null,
                'second_side_type' => $_payload['second_side'] && $_payload['second_side']['company'] ? $_payload['second_side']['company']->getMap() : null,
                'reason_id' => $order_id,
                'reason_type' => $this->orderContract->getMap(),
                'type' => ConstTransactionType::ORDER()->getValue(),
                'payment_type_id' => PaymentType::CASH,

                'remainder' => $remainder,
                'franchise_cost' => $franchise_cost,
                'side_cost' => $driver_price,
                'amount' => $price,

                'out' => false,
                'payed' => false,
            ]);
        });
    }

    /**
     * @inheritdoc
     */
    public function getPercentByDriver($driver_id, int $franchise_id = FRANCHISE_ID): int|string
    {
        $data = $this->driverTypeContract
            ->whereHas('drivers', fn(Builder $query) => $query
                ->where('driver_id', '=', $driver_id)
                ->where('signed', '=', true)
                ->where('active', '=', true))
            ->with(['franchise_options' => fn($query) => $query->where('driver_type_option.franchise_id', '=', $franchise_id)->where('valued', '=', true)])
            ->findFirst();

        return $data->franchise_options->first()->pivot->percent_value ?? 0;
    }
}
