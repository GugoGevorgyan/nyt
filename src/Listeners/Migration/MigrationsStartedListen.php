<?php

declare(strict_types=1);

namespace Src\Listeners\Migration;

use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Support\Facades\Artisan;

/**
 * Class MigrationsStartedListen
 * @package Src\Listeners\Migration
 */
class MigrationsStartedListen
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MigrationsStarted  $event
     * @return void
     */
    public function handle(MigrationsStarted $event): void
    {
//        Artisan::call('monitor:address-writer');
    }
}
