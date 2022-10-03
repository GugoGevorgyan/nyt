<?php

declare(strict_types=1);


namespace Src\Services\Terminal;


use ServiceEntity\Contract\BaseContract;

/**
 * Class TerminalServiceContract
 * @package Src\Services\Terminal
 */
interface TerminalServiceContract extends BaseContract
{
    /**
     * @param int $driver_id
     * @return array|null
     */
    public function getDriverDebt(int $driver_id): ?array;

    /**
     * @param int $driver_id
     * @param  $days
     * @return array
     */
    public function selectedWaybillDaysPrice(int $driver_id, $days): array;

    /**
     * @param $driver_id
     * @param $days
     * @return bool
     */
    public function checkDriverActiveWaybillsLimit($driver_id, $days): bool;

    /**
     * @param $driver_id
     * @param $cash
     * @return mixed
     */
    public function payBalance($driver_id, $cash): array;

    /**
     * @param $driver_id
     * @param $price
     * @param $type
     * @return array
     */
    public function addedCurrentCash($driver_id, $price, $type): array;

    /**
     * @param $driver_id
     * @param $cash
     * @param $deposit
     * @param bool $waybill
     * @return array|null
     */
    public function payDebt($driver_id, $cash, $deposit): ?array;

    /**
     * @param $driver_id
     * @param float $cash
     * @param float $balance
     * @param int $days
     * @return array|null
     */
    public function payWaybill($driver_id, float $cash = 0.0, float $balance = 0.0, int $days): ?array;

    /**
     * @param int $driver_id
     * @param int $days
     * @param bool $checked
     * @return array
     */
    public function createManualWaybill(int $driver_id, int $days, bool $checked = false): array;

    /**
     * @param int $waybill_id
     * @param $checked
     * @return bool
     */
    public function isToggleCheckedWaybill(int $waybill_id, bool $checked = false): bool;

    /**
     * @param int $driver_id
     * @return void
     */
    public function restoreCurrentWaybill(int $driver_id): void;

    /**
     * @param $transaction_id
     * @param $waybill
     * @return string
     */
    public function uploadWaybill($transaction_id, $waybill): string;

    /**
     * @param $driver_id
     * @param $contract
     * @param int $days
     * @return float|int|string
     */
    public function getDriverLatestComingWaybill($driver_id, $contract, int $days = 1): float|int|string;
}
