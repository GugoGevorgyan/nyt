<?php

declare(strict_types=1);


namespace Src\Services\Payment;

use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface PaymentServiceContract
 * @package Src\Services\PaymentType
 */
interface PaymentServiceContract extends BaseContract
{
    /**
     * @param  array  $values
     * @return Collection|null
     */
    public function getPaymentTypes(array $values = []): ?Collection;

    /**
     * @param  array  $card_data
     * @return mixed
     */
    public function addCardForClient(array $card_data): array;

    /**
     * @param  int  $driver_id
     * @param  float  $sum
     * @param  bool  $driver_fault
     * @param  object  $crash
     * @return mixed
     */
    public function carCrashCostDistributor($driver_id, $sum, bool $driver_fault, object $crash);

    /**
     * @param $order_id
     * @param $driver_id
     * @param $price
     * @param $payment_type
     * @return bool|array
     */
    public function driverPercent($order_id, $driver_id, $price, $payment_type): bool|array;

    /**
     * @param $order_id
     * @param $driver_id
     * @param $price
     * @param $payment_type
     */
    public function workerOrderReCalc($order_id, $driver_id, $price, $payment_type): void;

    /**
     * @param $driver_id
     * @param  int|mixed|null  $franchise_id
     * @return int|string
     */
    public function getPercentByDriver($driver_id, int $franchise_id = FRANCHISE_ID):int|string;
}
