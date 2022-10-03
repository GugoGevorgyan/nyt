<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Events\RefreshTokenCreated;
use Src\Events\App\AddressMonitorEvent;
use Src\Events\ClientCall\ClientCallCreateEvent;
use Src\Events\ClientCall\ClientCallUpdateEvent;
use Src\Events\CreatedSystemWorker;
use Src\Events\Driver\DriverUpdateEvent;
use Src\Events\Driver\CoordinatesUpdateEvent;
use Src\Events\Order\DistributeOrder;
use Src\Events\Order\OrderAcceptEvent;
use Src\Events\Order\OrderAttachEvent;
use Src\Events\Order\OrderCommonCreateEvent;
use Src\Events\Order\OrderCommonUpdateEvent;
use Src\Events\Order\OrderCreateEvent;
use Src\Events\Order\OrderShippedDriverEvent;
use Src\Events\Order\OrderStatusUpdate;
use Src\Events\Order\OrderUpdateEvent;
use Src\Events\SystemWorker\FranchiseTransactionCrud;
use Src\Events\TrafficSafetyCheckEvent;
use Src\Events\UpdatedMechanicInfo;
use Src\Events\Waybill\WaybillCreate;
use Src\Events\SystemWorker\WorkerOperatorCreateEvent;
use Src\Events\SystemWorker\WorkerOperatorUpdateEvent;
use Src\Listeners\AddressMonitor\AddressDataMonitorListen;
use Src\Listeners\AddressMonitor\ApiMonitoringListen;
use Src\Listeners\AdminCorporate\UpdateOrderListen;
use Src\Listeners\CoordsUpdateListener;
use Src\Listeners\ExpirationMailToDriver;
use Src\Listeners\ExpirationMailToParkManager;
use Src\Listeners\Migration\MigrationsStartedListen;
use Src\Listeners\Order\Calc\RealCounter;
use Src\Listeners\Order\Distributor\OrderDistributorStarter;
use Src\Listeners\Passport\PruneOldTokens;
use Src\Listeners\Passport\RevokeOldTokens;
use Src\Listeners\SendUpdatedMechanicInfoToAdmin;
use Src\Listeners\Worker\SendWorkerCreatedData;
use Src\Listeners\Worker\SendWorkerCreatedToAdmin;
use Src\Listeners\Worker\FranchiseTransactionCrudListen;
use Src\Listeners\Worker\WaybillListCreateListener;
use Src\Listeners\Worker\DispatcherClientCallCreateListener;
use Src\Listeners\Worker\DispatcherClientCallUpdateListener;
use Src\Listeners\Worker\DispatcherDriverActiveShippedListener;
use Src\Listeners\Worker\DispatcherDriverUpdateListener;
use Src\Listeners\Worker\DispatcherOperatorCreateListener;
use Src\Listeners\Worker\DispatcherOperatorUpdateListener;
use Src\Listeners\Worker\DispatcherOrderActiveShippedListener;
use Src\Listeners\Worker\DispatcherOrderCommonListener;
use Src\Listeners\Worker\DispatcherOrderCreateListen;
use Src\Listeners\Worker\DispatcherOrderUpdateListener;
use Src\Listeners\Worker\OperatorDriverActiveShippedListener;
use Src\Listeners\Worker\OperatorDriverUpdateListener;
use Src\Listeners\Worker\OperatorOrderActiveShippedListener;
use Src\Listeners\Worker\OperatorOrderAttachListener;
use Src\Listeners\Worker\OperatorOrderCommonListener;
use Src\Listeners\Worker\OperatorOrderCreateListen;
use Src\Listeners\Worker\OperatorOrderUpdateListener;

/**
 * Class EventServiceProvider
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        MigrationsStarted::class => [
            MigrationsStartedListen::class
        ],

        UpdatedMechanicInfo::class => [
            SendUpdatedMechanicInfoToAdmin::class
        ],

        CreatedSystemWorker::class => [
            SendWorkerCreatedData::class,
            SendWorkerCreatedToAdmin::class
        ],

        TrafficSafetyCheckEvent::class => [
            ExpirationMailToDriver::class,
            ExpirationMailToParkManager::class
        ],

        // ORDER Distributes search driver
        DistributeOrder::class => [
            OrderDistributorStarter::class
        ],

        AddressMonitorEvent::class => [
            ApiMonitoringListen::class,
            AddressDataMonitorListen::class,
        ],

        AccessTokenCreated::class => [
            RevokeOldTokens::class,
        ],
        RefreshTokenCreated::class => [
            PruneOldTokens::class,
        ],

        // Waybill
        WaybillCreate::class => [
            WaybillListCreateListener::class,
        ],

        /*Order*/
        OrderCreateEvent::class => [
            OperatorOrderCreateListen::class,
            DispatcherOrderCreateListen::class,
            UpdateOrderListen::class
        ],

        OrderUpdateEvent::class => [
            OperatorOrderUpdateListener::class,
            DispatcherOrderUpdateListener::class,
        ],

        OrderAttachEvent::class => [
            OperatorOrderAttachListener::class
        ],

        OrderAcceptEvent::class => [
            OperatorOrderUpdateListener::class,
            DispatcherOrderUpdateListener::class
        ],

        OrderCommonCreateEvent::class => [
            DispatcherOrderCommonListener::class,
            OperatorOrderCommonListener::class
        ],

        OrderCommonUpdateEvent::class => [
            DispatcherOrderCommonListener::class,
            OperatorOrderCommonListener::class
        ],

        OrderShippedDriverEvent::class => [
            DispatcherDriverActiveShippedListener::class,
            OperatorDriverActiveShippedListener::class,
            DispatcherOrderActiveShippedListener::class,
            OperatorOrderActiveShippedListener::class
        ],

        OrderStatusUpdate::class => [
            UpdateOrderListen::class
        ],

        DriverUpdateEvent::class => [
            OperatorDriverUpdateListener::class,
            DispatcherDriverUpdateListener::class,
        ],

        CoordinatesUpdateEvent::class => [
            RealCounter::class,
            CoordsUpdateListener::class,
        ],

        /*Worker operator*/
        WorkerOperatorCreateEvent::class => [
            DispatcherOperatorCreateListener::class
        ],

        WorkerOperatorUpdateEvent::class => [
            DispatcherOperatorUpdateListener::class
        ],

        /*Client call*/
        ClientCallCreateEvent::class => [
            DispatcherClientCallCreateListener::class
        ],

        ClientCallUpdateEvent::class => [
            DispatcherClientCallUpdateListener::class
        ],

        FranchiseTransactionCrud::class => [
            FranchiseTransactionCrudListen::class
        ]
    ];
}
