<?php

declare(strict_types=1);

namespace ServiceEntity\GeoIP\Console;

use Exception;
use Illuminate\Console\Command;
use ServiceEntity\GeoIP\GeoIP;

use function get_class;

class Update extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'geoip:update';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update GeoIP database files to the latest version';

    /**
     * Execute the console command for Laravel 5.5 and newer.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        $this->fire();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function fire(): void
    {
        // Get default service
        $service = app('geoip')->getService();

        // Ensure the selected service supports updating
        if (false === method_exists($service, 'update')) {
            $this->info('The current service "'.get_class($service).'" does not support updating.');

            return;
        }

        $this->comment('Updating...');

        // Perform update
        if ($result = $service->update()) {
            $this->info($result);
        } else {
            $this->error('Update failed!');
        }
    }
}
