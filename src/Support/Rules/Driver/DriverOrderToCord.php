<?php

declare(strict_types=1);

namespace Src\Support\Rules\Driver;

use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class DriverOrderToCord
 * @package Src\Support\Rules\Driver
 */
class DriverOrderToCord implements Rule
{
    /**
     * @var OrderShippedDriverContract|Application|mixed
     */
    protected $shipmentContract;

    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
        $this->shipmentContract = app(OrderShippedDriverContract::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  array  $value  ['order_id', 'hash','lat', 'lut]
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $order = $this->shipmentContract
            ->where($attribute, '=', $value['hash'])
            ->where('driver_id', '=', user()->{user()->getKeyName()})
            ->whereHas(
                'order',
                static function (Builder $q_order) use ($value) {
                    $q_order->where('order_id', '=', $value['order_id'])
                        ->select(['order_id']);
                }
            )
            ->findFirst(['driver_id', 'order_id', $attribute]);

        if (!$order) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid Data.';
    }
}
