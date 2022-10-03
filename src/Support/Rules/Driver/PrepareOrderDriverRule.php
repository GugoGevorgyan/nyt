<?php

declare(strict_types=1);

namespace Src\Support\Rules\Driver;

use Illuminate\Contracts\Validation\Rule;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCommon\OrderCommonContract;

/**
 * Class CommonOrderHasDriver
 * @package Src\Support\Rules\Driver
 */
class PrepareOrderDriverRule implements Rule
{
    /**
     * @var string
     */
    protected string $message;
    /**
     * @var
     */
    protected $orderId;
    /**
     * @var
     */
    protected $driverId;

    /**
     * Create a new rule instance.
     *
     * @param $driver_id
     * @param $order_id
     */
    public function __construct($driver_id, $order_id)
    {
        $this->orderId = $order_id;
        $this->driverId = $driver_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $this->request();
    }

    /**
     * @return bool
     */
    protected function request(): bool
    {
        $order = app(OrderCommonContract::class)
            ->with(['preorder' => fn($query) => $query->select(['preorder_id', 'order_id'])])
            ->where('order_id', '=', $this->orderId)
            ->findFirst(['order_id', 'driver']);

        if (!$order && !\in_array($this->driverId, $order->driver['ids'], true)) {
            $this->message = (string)trans('validation.custom.rules.You do not have access to this order');
            return false;
        }

        if ($order->preorder && !\in_array($this->driverId, $order->driver['ids'], true)) {
            $this->message = (string)trans('validation.custom.rules.You do not have access to this order');
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
