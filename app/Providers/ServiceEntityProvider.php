<?php

declare(strict_types=1);

namespace App\Providers;

use DirectoryIterator;
use Illuminate\Support\ServiceProvider;
use ServiceEntity\Providers\BaseServiceProvider;
use Src\ServicesCrud\Area\AreaCrud;
use Src\ServicesCrud\Area\AreaCrudContract;
use Src\ServicesCrud\Driver\DriverCrud;
use Src\ServicesCrud\Driver\DriverCrudContract;
use Src\ServicesCrud\Order\OrderCrud;
use Src\ServicesCrud\Order\OrderCrudContract;
use Src\ServicesCrud\Tariff\TariffCrud;
use Src\ServicesCrud\Tariff\TariffCrudContract;

/**
 * Class ServiceEntityProvider
 * @package App\Providers
 */
class ServiceEntityProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(BaseServiceProvider::class);

        $_root_directory = base_path().DS.'src'.DS.'Services'.DS;

        $dir = new DirectoryIterator($_root_directory);
        $folders = [];

        foreach ($dir as $file_info) {
            if ($file_info->isDir() && !$file_info->isDot()) {
                $folders[] = $file_info->getFilename();
            }
        }

        $namespace = 'Src\Services';

        foreach ($folders as $folder) {
            $contract = count(scandir($_root_directory.$folder)) >= 6 ? 4 : 3;
            $service = count(scandir($_root_directory.$folder)) >= 6 ? 3 : 2;
            $pattern = count(scandir($_root_directory.$folder)) >= 6 ? 2 : null;

            $contract_file = str_replace('.php', '', scandir($_root_directory.$folder)[$contract]);
            $service_file = str_replace('.php', '', scandir($_root_directory.$folder)[$service]);

            $this->app->bindIf("$namespace\\$folder\\$contract_file", "$namespace\\$folder\\$service_file");

            if ($pattern) {
                $pattern_file = str_replace('.php', '', scandir($_root_directory.$folder)[$pattern]);
                $this->app->singleton(strtolower($folder).'Pattern', fn() => app()->make("$namespace\\$folder\\$pattern_file"));
            }
        }

        // CRUD @todo
        $this->app->bindIf(OrderCrudContract::class, OrderCrud::class);
        $this->app->bindIf(TariffCrudContract::class, TariffCrud::class);
        $this->app->bindIf(AreaCrudContract::class, AreaCrud::class);
        $this->app->bindIf(DriverCrudContract::class, DriverCrud::class);
    }
}
