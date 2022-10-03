<?php

declare(strict_types=1);

namespace Src\Listeners;

use Auth;
use Laravel\Passport\Events\AccessTokenCreated;
use Src\Models\Role\Role;
use Src\Repositories\Driver\DriverContract;

/**
 * Class UserAuthListener
 * @package Src\Listeners
 */
class DriverAuthListener
{
    /**
     * Create the event listener.
     *
     * @param  DriverContract  $driverContract
     */
    public function __construct(protected DriverContract $driverContract)
    {
    }

    /**
     * @param  AccessTokenCreated  $event_data
     */
    public function handle(AccessTokenCreated $event_data): void
    {
        $driver = $this->driverContract->update($event_data->userId, ['logged' => true, 'online' => true]);
        $driver->assignRole(Role::DRIVER_API);

        Auth::guard((string)session('assigned_guard'))->loginUsingId($event_data->userId);
    }
}
