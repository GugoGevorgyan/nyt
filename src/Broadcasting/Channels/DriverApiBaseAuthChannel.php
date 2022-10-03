<?php

declare(strict_types=1);

namespace Src\Broadcasting\Channels;

use Src\Models\Driver\Driver;

/**
 * Class DriverApiBaseAuthChannel
 * @package Src\Broadcasting\Channels
 */
class DriverApiBaseAuthChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  Driver  $driver
     * @param $driver_id
     * @param $phone
     * @param $car_id
     * @param $franchise_id
     * @return bool
     */
    public function join(Driver $driver, $driver_id, $phone, $car_id, $franchise_id): bool
    {
        return !($driver->driver_id !== (int)$driver_id && $driver->phone !== (int)$phone && $driver->car_id !== $car_id && $driver->current_franchise_id !== $franchise_id);
    }
}
