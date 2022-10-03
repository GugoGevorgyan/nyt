<?php

declare(strict_types=1);

namespace Src\Support\Rules\Driver;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Src\Repositories\OrderInProcessRoad\OrderInProcessRoadContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;

/**
 * Class DriverOnStartSelectRouteRule
 * @package Src\Support\Rules\Driver
 */
class DriverOnStartSelectRouteRule implements Rule
{
    /**
     * @var OrderShippedDriverContract
     */
    protected $shipmentContract;

    /**
     * @var OrderInProcessRoadContract|Application|mixed
     */
    protected $orderInProcessRoadContract;

    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
        $this->shipmentContract = app(OrderShippedDriverContract::class);
        $this->orderInProcessRoadContract = app(OrderInProcessRoadContract::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $values
     * @return bool
     */
    public function passes($attribute, $values): bool
    {
        return $this->shipmentContract->where($attribute, '=', $values['hash'])
            ->where('driver_id', '=', user()->{user()->getKeyName()})
            ->whereHas(
                'order',
                fn (Builder $q_on_way) => $q_on_way->where('order_id', '=', $values['order_id'])
            )
            ->when(
                $values['selected_route'],
                fn (Builder $q_in_process, $values) => $q_in_process->whereHas(
                    'in_process_roads',
                    fn (Builder $process_road_query) => $process_road_query->where('order_in_process_road_id', '=', $values),
                ),
            )
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
