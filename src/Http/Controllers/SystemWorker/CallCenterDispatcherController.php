<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Collective\Annotations\Routing\Annotations\Annotations\Put;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use JetBrains\PhpStorm\ArrayShape;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\DispatcherOperatorAttachOrderRequest;
use Src\Http\Requests\SystemWorker\DriverUnpinOrderRequest;
use Src\Http\Requests\SystemWorker\OrderReCalcEditRequest;
use Src\Http\Requests\SystemWorker\ReManualyOrderRequest;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Models\CompletedOrderResource;
use Src\Http\Resources\PaginateResource;
use Src\Http\Resources\Worker\CallCenter\OrdersPaginate;
use Src\Repositories\Airport\AirportContract;
use Src\Repositories\DriverStatus\DriverStatusContract;
use Src\Repositories\Metro\MetroContract;
use Src\Repositories\RailwayStation\RailwayStationContract;
use Src\Services\Car\CarServiceContract;
use Src\Services\ClientCall\ClientCallServiceContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Franchise\FranchiseServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\Payment\PaymentServiceContract;
use Src\Services\Region\RegionServiceContract;
use Src\Services\Worker\WorkerServiceContract;
use Src\ServicesCrud\Driver\DriverCrudContract;
use Src\ServicesCrud\Order\OrderCrudContract;
use Src\Support\Facades\Sms;

/**
 * Class CallCenterDispatcherController
 * @package Src\Http\Controllers\SystemWorker
 */
class CallCenterDispatcherController extends Controller
{
    /**
     * CallCenterDispatcherController constructor.
     * @param  OrderServiceContract  $orderService
     * @param  DriverServiceContract  $driverService
     * @param  ClientCallServiceContract  $clientCallService
     * @param  OrderCrudContract  $orderCrud
     * @param  FranchiseServiceContract  $franchiseService
     * @param  ClientCallServiceContract  $callServiceContract
     * @param  CarServiceContract  $carServiceContract
     * @param  DriverCrudContract  $driverCrud
     * @param  PaymentServiceContract  $paymentService
     * @param  RailwayStationContract  $railwayStationContract
     * @param  AirportContract  $airportContract
     * @param  MetroContract  $metroContract
     * @param  WorkerServiceContract  $workerService
     * @param  RegionServiceContract  $regionService
     * @param  DriverStatusContract  $driverStatusContract
     */
    public function __construct(
        protected OrderServiceContract $orderService,
        protected DriverServiceContract $driverService,
        protected ClientCallServiceContract $clientCallService,
        protected OrderCrudContract $orderCrud,
        protected FranchiseServiceContract $franchiseService,
        protected ClientCallServiceContract $callServiceContract,
        protected CarServiceContract $carServiceContract,
        protected DriverCrudContract $driverCrud,
        protected PaymentServiceContract $paymentService,
        protected RailwayStationContract $railwayStationContract,
        protected AirportContract $airportContract,
        protected MetroContract $metroContract,
        protected WorkerServiceContract $workerService,
        protected RegionServiceContract $regionService,
        protected DriverStatusContract $driverStatusContract
    ) {
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): Factory|View|Application
    {
        $country_code = $this->franchiseService->getFranchisePhoneCode();
        $sub_phone = $this->workerService->getSubPhone(auth()->user()->system_worker_id);
        $pending_orders = $this->orderService->operatorPendingOrders();
        $order_statuses = $this->orderService->getOrderStatuses();
        $order_types = $this->orderService->getOrderTypes();
        $driver_statuses = $this->driverStatusContract->getDriverStatuses();
        $car_classes = $this->carServiceContract->getFranchiseCarClasses(FRANCHISE_ID);
        $car_options = $this->carServiceContract->getAllOptions();
        $payment_types = $this->paymentService->getPaymentTypes();
        $calls = $sub_phone ? $this->clientCallService->getWorkerCalls($sub_phone->number, 7) : '';
        $drivers = $this->driverCrud->callCenterGetDrivers();
        [$railway_stations, $airports, $metros] = $this->regionService->getTransports();

        return view(
            'system-worker.call-center-dispatcher.index',
            compact(
                'sub_phone',
                'country_code',
                'pending_orders',
                'order_types',
                'order_statuses',
                'driver_statuses',
                'car_classes',
                'car_options',
                'payment_types',
                'calls',
                'railway_stations',
                'airports',
                'metros',
                'drivers'
            ));
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response|BaseResource|PaginateResource
     */
    public function ordersPaginate(Request $request): PaginateResource|Response|BaseResource|Application|ResponseFactory
    {
        $data = $this->orderService->callCenterDispatcherOrderPaginate($request);

        if (!$data) {
            return response(['message' => 'error'], 400);
        }

        return (new PaginateResource($data->toArray()))->collectionClass(OrdersPaginate::class);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function getPendingOrders(): Response|Application|ResponseFactory
    {
        return response($this->orderService->dispatcherPendingOrders(), 200);
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function operatorsPaginate(Request $request): Response|Application|ResponseFactory
    {
        return response($this->workerService->franchiseOperatorsPaginate($request));
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function callsPaginate(Request $request): Response|Application|ResponseFactory
    {
        return response($this->callServiceContract->dispatcherCallsPaginate($request), 200);
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function boardsPaginate(Request $request): Response|Application|ResponseFactory
    {
        return response($this->driverCrud->callCenterDriversPaginate($request));
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function getOperators(): Response|Application|ResponseFactory
    {
        return response($this->workerService->getFranchiseOperators());
    }

    /**
     * @param  DispatcherOperatorAttachOrderRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function operatorAttachOrder(DispatcherOperatorAttachOrderRequest $request): Response|Application|ResponseFactory
    {
        return $this->workerService->operatorAttachOrder($request)
            ? response(['message' => trans('messages.order_attached_to_operator')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  OrderReCalcEditRequest  $request
     * @param  int  $order_id
     * @return Application|ResponseFactory|Response|CompletedOrderResource
     */
    public function operatorReCalcOrder(OrderReCalcEditRequest $request, int $order_id): CompletedOrderResource|Response|Application|ResponseFactory
    {
        $result = $this->workerService->recalculateOrder($order_id, $request->distance, $request->duration, $request->cross, $request->cost);

        if (!$result) {
            return response(['message' => 'Error in recalculate'], 500);
        }

        return new CompletedOrderResource($result);
    }

    /**
     * @param  ReManualyOrderRequest  $request
     * @param  int  $order_id
     * @return Response|Application|ResponseFactory
     */
    public function changeOrderDistToManual(ReManualyOrderRequest $request, int $order_id): Response|Application|ResponseFactory
    {
        $result = $this->workerService->changeOrderToManuality($order_id, get_user_id());

        if (!$result) {
            return response(['message' => 'Невозможная операция на данный момент'], 422);
        }

        return response(['message' => 'Заказ переведен на ручное распределение']);
    }

    /**
     * @param  DriverUnpinOrderRequest  $request
     * @return Response|Application|ResponseFactory
     * @Put('dr_ord_unpin/{driver_id}', where={'driver_id': '[0-9]+'})
     */
    public function driverOrderUnpin(DriverUnpinOrderRequest $request): Response|Application|ResponseFactory
    {
        $unpin_result = $this->workerService->orderDriverUnpin($request->order_id, $request->driver_id);

        if (!$unpin_result) {
            return response(['message' => 'SERVER ERROR'], 500);
        }

        return response(['message' => 'Order unpin to driver']);
    }

    /**
     * @param  Request  $request
     * @return array
     */
    #[ArrayShape(['message' => 'string'])] public function sendMessage(Request $request): array
    {
        Sms::send($request->phone, $request->text);

        return ['message' => 'Письмо отправлено'];
    }
}
