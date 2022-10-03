<?php

declare(strict_types=1);

namespace Src\Support\Rules\Driver;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Models\Order\OrderShippedStatus;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;

/**
 * Class DriverOrderShippedRule
 * @package Src\Support\Rules\Driver
 */
class DriverOrderShippedRule implements Rule
{
    /**
     * @var string
     */
    protected string $message = '';

    /**
     * Create a new rule instance.
     *
     */
    public function __construct(protected OrderShippedDriverContract $shippedContract)
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool|null ?
     * @noinspection MultipleReturnStatementsInspection
     */
    public function passes($attribute, $value): ?bool
    {
        $shipped = $this->shippedContract
            ->where($attribute, '=', $value['hash'])
            ->whereHas('order', fn(Builder $q_order) => $q_order->where('order_id', '=', $value['order_id']))
            ->with(['common' => fn(BelongsTo $q_order) => $q_order->where('manual', '=', true)->select(['order_id', 'manual'])])
            ->findFirst(['accept_hash', 'order_id', 'created_at', 'status_id', 'order_id']);

        if (!$shipped) {
            $this->message = 'Hash INValid';
            return false;
        }

        if ($shipped->common && $shipped->status_id === OrderShippedStatus::PRE_MANUAL) {
            $this->message = 'Order is canceled';
            return false;
        }

        if (now()->diffInSeconds($shipped->created_at) > (int)config('nyt.driver_response_time')) {
            $this->message = 'Order time left';
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
