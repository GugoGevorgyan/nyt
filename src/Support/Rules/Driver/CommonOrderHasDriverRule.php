<?php

declare(strict_types=1);


namespace Src\Support\Rules\Driver;


use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\Rule;
use Src\Models\Order\OrderShippedStatus;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;

/**
 * Class DriverScheduleOrderRule
 * @package Src\Support\Rules\Driver
 */
class CommonOrderHasDriverRule implements Rule
{
    /**
     * @var OrderShippedDriverContract
     */
    protected OrderShippedDriverContract $shippedContract;
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
     * @throws BindingResolutionException
     */
    public function __construct($driver_id, $order_id)
    {
        $this->orderId = $order_id;
        $this->driverId = $driver_id;

        $this->shippedContract = app()->make(OrderShippedDriverContract::class);
    }

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool|null
     */
    public function passes($attribute, $value): ?bool
    {
        $accepted = $this->shippedContract
            ->where('order_id', '=', $this->orderId)
            ->where('driver_id', '=', $this->driverId)
            ->where('common', '=', true)
            ->firstLatest( 'created_at',[$this->shippedContract->getKeyName(), 'driver_id', 'order_id', 'common', 'status_id']);

        if (!$accepted) {
            $this->message = trans('validation.custom.rules.You do not have access to this order');
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return $this->message;
    }
}
