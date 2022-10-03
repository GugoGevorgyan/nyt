<?php

declare(strict_types=1);

namespace Src\Services\Payment;

/**
 *
 */
trait PaymentTrait
{
    /**
     * @param $order_id
     * @param $driver_id
     * @return array{driver: array|object, percent: int|string, second_side: object|array, last_transaction: object|array, last_order_transaction: object|array, last_franchise_transaction: object|array}
     */
    private function orderCalcReCalcPayload($order_id, $driver_id): array
    {
        $driver = $this->driverContract
            ->with([
                'cash' => fn($query) => $query->select(['driver_id', 'debt', 'balance']),
                'car' => fn($query) => $query->select(['car_id', 'park_id']),
            ])
            ->find($driver_id, ['driver_id', 'current_franchise_id', 'car_id']);

        $percent = $this->getPercentByDriver($driver->driver_id, $driver->current_franchise_id);

        $second_side = $this->orderContract
            ->has('company')
            ->with(['company' => fn($query) => $query->select(['companies.company_id'])])
            ->find($order_id, ['order_id']);

        $last_transaction = $this->transactionContract
                ->where('side_id', '=', $driver_id)
                ->where('side_type', '=', $this->driverContract->getMap())
                ->firstLatest('created_at', ['side_id', 'side_type', 'side_cost', 'remainder', 'amount', 'franchise_cost', 'created_at'])
            ?? [];
        $last_order_transaction = $this->transactionContract
                ->where('side_id', '=', $driver_id)
                ->where('side_type', '=', $this->driverContract->getMap())
                ->where('reason_type', '=', $this->orderContract->getMap())
                ->firstLatest('created_at', ['reason_id', 'reason_type', 'side_cost', 'remainder', 'amount', 'created_at'])
            ?? [];
        $last_franchise_transaction = $this->transactionContract
                ->where('franchise_id', '=', $driver->current_franchise_id)
                ->firstLatest('created_at', ['reason_id', 'franchise_id', 'franchise_cost', 'created_at'])
            ?? [];

        return compact('driver', 'percent', 'second_side', 'last_transaction', 'last_order_transaction', 'last_franchise_transaction');
    }
}
