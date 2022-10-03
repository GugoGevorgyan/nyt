<?php

declare(strict_types=1);

namespace Src\Support\Rules\Driver;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Src\Models\Driver\DriverStatus;
use Src\Repositories\OrderInProcessRoad\OrderInProcessRoadContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Support\Rules\Cords\ValidLatitude;
use Src\Support\Rules\Cords\ValidLongitude;

/**
 * Class DriverOrderOnStartRule
 * @package Src\Support\Rules\Driver
 */
class DriverOrderOnStartRule implements Rule
{
    /**
     * @var string
     */
    protected string $message = 'The validation error message.';

    /**
     * Create a new rule instance.
     */
    public function __construct(protected OrderShippedDriverContract $shipmentContract, protected OrderInProcessRoadContract $orderInProcessRoadContract)
    {
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
        $route_or_to_lat = !$values['to_lut'] ? $values['route_or_to_lat'] : false;
        $lat_validation = new ValidLatitude();
        $lut_validation = new ValidLongitude();

        // COORDINATE VALIDATION
        if (!$route_or_to_lat && $values['to_lut']) {
            $valid_lat = $lat_validation->passes(null, $values['route_or_to_lat']);
            $valid_lut = $lut_validation->passes(null, $values['to_lut']);

            if (!$valid_lat) {
                $this->message = $lat_validation->message();
                return false;
            }

            if (!$valid_lut) {
                $this->message = $lut_validation->message();
                return false;
            }
        }

        // VALIDATE HASH, DRIVER_ID, ORDER_ID
        $check_data = $this->shipmentContract
            ->where($attribute, '=', $values['hash'])
            ->where('driver_id', '=', user()->{user()->getKeyName()})
            ->whereHas(
                'order',
                fn(Builder $q_on_way) => $q_on_way->where('order_id', '=', $values['order_id'])
            )
            ->findFirst(['order_shipped_driver_id', 'driver_id', 'order_id', 'in_order_hash']);

        if (!$check_data) {
            $this->message = 'Hash or Order id invalid';
            return false;
        }

        //VALIDATE DRIVER STATUS
        $driver_status = $check_data
            ->whereHas(
                'driver',
                fn(Builder $q_driver) => $q_driver->where('current_status_id', '=', DriverStatus::getStatusId(DriverStatus::DRIVER_IN_PLACE))
            )->first();

        if (!$driver_status) {
            $this->message = 'Your status is invalid you are already on started';
            return false;
        }

        $in_process_roads = $route_or_to_lat ? $check_data->whereHas(
            'in_process_roads',
            fn(Builder $process_road_query) => $process_road_query->where('order_in_process_road_id', '=', $route_or_to_lat)
        )->first() : true;

        if (!$in_process_roads) {
            $this->message = 'Your Road id is invalid';
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
