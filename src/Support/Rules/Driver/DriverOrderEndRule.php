<?php

declare(strict_types=1);

namespace Src\Support\Rules\Driver;

use Illuminate\Contracts\Validation\Rule;
use Src\Models\Order\OrderStatus;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;

/**
 * Class DriverOrderEndRule
 * @package Src\Support\Rules\Driver
 */
class DriverOrderEndRule implements Rule
{
    /**
     * @var string
     */
    protected string $message = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(protected OrderShippedDriverContract $orderShipmentContract, protected OrderContract $orderContract)
    {
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
        if (!$this->orderShipmentContract->whereExistsExist($attribute, '=', $value['hash'])) {
            $this->message = 'Hash Error';
            return false;
        }

        if (!$this->orderContract->whereExistsExist('order_id', '=', $value['order_id'])) {
            $this->message = 'Order id Error';
            return false;
        }

        $completed = $this->orderContract
            ->where('order_id', '=', $value['order_id'])
            ->where('status_id', '=', OrderStatus::getStatusId(OrderStatus::ORDER_COMPLETED))
            ->exists();

        $canceled = $this->orderContract
            ->where('order_id', '=', $value['order_id'])
            ->where('status_id', '=', OrderStatus::getStatusId(OrderStatus::ORDER_CANCELED))
            ->exists();

        if ($completed || $canceled) {
            $this->message = 'Order already canceled';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
