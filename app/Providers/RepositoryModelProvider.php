<?php

declare(strict_types=1);

namespace App\Providers;

use DirectoryIterator;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Repository\Providers\RepositoryServiceProvider;
use ServiceEntity\Providers\BaseServiceProvider;
use Src\Models\Car\Car;
use Src\Models\Car\CarCrash;
use Src\Models\Client\BeforeAuthClient;
use Src\Models\Client\Client;
use Src\Models\Client\ClientCall;
use Src\Models\Corporate\AdminCorporate as AdminCorporateModel;
use Src\Models\Corporate\Company;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverWallet;
use Src\Models\Franchise\FranchiseTransaction;
use Src\Models\Order\CanceledOrder;
use Src\Models\Order\CompletedOrder;
use Src\Models\Order\Order;
use Src\Models\Order\OrderCommon;
use Src\Models\Order\OrderCorporate;
use Src\Models\Order\OrderInProcessRoad;
use Src\Models\Order\OrderOnWayRoad;
use Src\Models\Order\OrderShippedDriver;
use Src\Models\SystemUsers\SystemWorker;
use Src\Models\SystemUsers\WorkerDispatcher;
use Src\Models\SystemUsers\WorkerOperator;
use Src\Models\Tariff\Tariff;
use Src\Models\Tariff\TariffDestination;
use Src\Models\Tariff\TariffRegionBehind;
use Src\Models\Tariff\TariffRegionCity;
use Src\Models\Tariff\TariffRent;
use Src\Models\Terminal\Waybill;
use Src\Models\TransportStations\Airport;
use Src\Models\TransportStations\Metro;
use Src\Models\TransportStations\RailwayStation;
use Src\Observers\CarObserver;
use Src\Observers\ClientCallObserver;
use Src\Observers\DriverObserver;
use Src\Observers\DriverWalletObserver;
use Src\Observers\FranchiseTransactionObserver;
use Src\Observers\OrderCommonObserver;
use Src\Observers\OrderCorporateObserver;
use Src\Observers\OrderObserver;
use Src\Observers\OrderShippedDriverObserver;
use Src\Observers\PassportClientObserver;
use Src\Observers\SystemWorkerObserver;
use Src\Observers\WaybillObserver;
use Src\Observers\WorkerOperatorObserver;

/**
 * Class RepositoryModelProvider
 *
 * @package App\Providers
 */
class RepositoryModelProvider extends ServiceProvider
{

    /**
     * Register BOOT services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->maps();
        $this->observers();
    }

    protected function maps(): void
    {
        Relation::morphMap(
            [
                (new Order())->getMap() => Order::class,
                (new OrderInProcessRoad())->getMap() => OrderInProcessRoad::class,
                (new OrderOnWayRoad())->getMap() => OrderOnWayRoad::class,
                (new Driver())->getMap() => Driver::class,
                (new Client())->getMap() => Client::class,
                (new BeforeAuthClient())->getMap() => BeforeAuthClient::class,
                (new SystemWorker())->getMap() => SystemWorker::class,
                (new AdminCorporateModel())->getMap() => AdminCorporateModel::class,
                (new Company())->getMap() => Company::class,
                (new WorkerDispatcher())->getMap() => WorkerDispatcher::class,
                (new WorkerOperator())->getMap() => WorkerOperator::class,
                (new Tariff())->getMap() => Tariff::class,
                (new TariffRegionCity())->getMap() => TariffRegionCity::class,
                (new TariffDestination())->getMap() => TariffDestination::class,
                (new TariffRegionBehind())->getMap() => TariffRegionBehind::class,
                (new TariffRent())->getMap() => TariffRent::class,
                (new CanceledOrder())->getMap() => CanceledOrder::class,
                (new CompletedOrder())->getMap() => CompletedOrder::class,
                (new Airport())->getMap() => Airport::class,
                (new RailwayStation())->getMap() => RailwayStation::class,
                (new Metro())->getMap() => Metro::class,
                (new CarCrash())->getMap() => CarCrash::class,
                (new Waybill())->getMap() => Waybill::class,
                (new DriverWallet())->getMap() => DriverWallet::class,
            ]
        );
    }

    public function observers(): void
    {
        Order::observe(OrderObserver::class);
        \Src\Models\Oauth\Client::observe(PassportClientObserver::class);
        SystemWorker::observe(SystemWorkerObserver::class);
        WorkerOperator::observe(WorkerOperatorObserver::class);
        Driver::observe(DriverObserver::class);
        ClientCall::observe(ClientCallObserver::class);
        Car::observe(CarObserver::class);
        OrderShippedDriver::observe(OrderShippedDriverObserver::class);
        OrderCommon::observe(OrderCommonObserver::class);
        Waybill::observe(WaybillObserver::class);
        FranchiseTransaction::observe(FranchiseTransactionObserver::class);
        DriverWallet::observe(DriverWalletObserver::class);
        OrderCorporate::observe(OrderCorporateObserver::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(RepositoryServiceProvider::class);

        $_root_directory = base_path().DS.'src'.DS.'Repositories'.DS;

        $dir = new DirectoryIterator($_root_directory);
        $folders = [];

        foreach ($dir as $file_info) {
            if ($file_info->isDir() && !$file_info->isDot()) {
                $folders[] = $file_info->getFilename();
            }
        }

        $namespace = 'Src\Repositories';

        foreach ($folders as $folder) {
            $contract_file = str_replace('.php', '', scandir($_root_directory.$folder)[2]);
            $repo_file = str_replace('.php', '', scandir($_root_directory.$folder)[3]);

            $this->app->bindIf("$namespace\\$folder\\$contract_file", "$namespace\\$folder\\$repo_file");
        }
    }
}
