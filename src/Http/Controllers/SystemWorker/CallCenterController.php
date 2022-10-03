<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Collective\Annotations\Routing\Annotations\Annotations\Post;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Src\Core\Enums\ConstQueue;
use Src\Core\Enums\ConstRedis;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\CallCenterAddressByCordsRequest;
use Src\Http\Requests\SystemWorker\CallCenterCallAnsweredRequest;
use Src\Http\Requests\SystemWorker\CallCenterCallEndRequest;
use Src\Http\Requests\SystemWorker\CallCenterCallStartRequest;
use Src\Http\Requests\SystemWorker\CallCenterCancelOrderRequest;
use Src\Http\Requests\SystemWorker\CallCenterConnectWorkerRequest;
use Src\Http\Requests\SystemWorker\CallCenterCreateClientRequest;
use Src\Http\Requests\SystemWorker\CallCenterFindCompaniesRequest;
use Src\Http\Requests\SystemWorker\CallCenterGetCallsRequest;
use Src\Http\Requests\SystemWorker\CallCenterGetOrderPriceRequest;
use Src\Http\Requests\SystemWorker\CallCenterOrderCommentCreateRequest;
use Src\Http\Requests\SystemWorker\CallCenterOrderCreateRequest;
use Src\Http\Requests\SystemWorker\CallCenterOrderFeedbackCreateRequest;
use Src\Http\Requests\SystemWorker\CallCenterSlipUpdateRequest;
use Src\Http\Requests\SystemWorker\CallCenterUpdateAtcLoggedRequest;
use Src\Http\Requests\SystemWorker\CallCenterUpdateClientRequest;
use Src\Http\Requests\SystemWorker\CoordinatesByAddressRequest;
use Src\Http\Requests\SystemWorker\OrderSendDriverListRequest;
use Src\Http\Requests\SystemWorker\SendClientNotificationRequest;
use Src\Http\Requests\SystemWorker\SendDriverNotificationRequest;
use Src\Http\Resources\App\InitCoinResource;
use Src\Http\Resources\Worker\CallCenter\DriverByTypeDistanceResource;
use Src\Http\Resources\Worker\CallCenter\OrderHistoryResource;
use Src\Jobs\SendNotifies;
use Src\Repositories\Airport\AirportContract;
use Src\Repositories\DriverStatus\DriverStatusContract;
use Src\Repositories\Metro\MetroContract;
use Src\Repositories\RailwayStation\RailwayStationContract;
use Src\Services\Car\CarServiceContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\ClientCall\ClientCallServiceContract;
use Src\Services\Company\CompanyServiceContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Franchise\FranchiseServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\OrderEnd\OrderEndService;
use Src\Services\Payment\PaymentServiceContract;
use Src\Services\Region\RegionServiceContract;
use Src\Services\Worker\WorkerServiceContract;
use Src\ServicesCrud\Driver\DriverCrudContract;
use Src\ServicesCrud\Order\OrderCrudContract;

/**
 * Class CallCenterController
 * @package Src\Http\Controllers\SystemWorker
 */
class CallCenterController extends Controller
{
    /**
     * CallCenterController constructor.
     * @param  OrderServiceContract  $orderService
     * @param  DriverServiceContract  $driverService
     * @param  OrderCrudContract  $orderCrudService
     * @param  ClientServiceContract  $clientService
     * @param  ClientCallServiceContract  $clientCallService
     * @param  CompanyServiceContract  $companyService
     * @param  FranchiseServiceContract  $franchiseService
     * @param  DriverCrudContract  $driverCrud
     * @param  CarServiceContract  $carService
     * @param  PaymentServiceContract  $paymentService
     * @param  RailwayStationContract  $railwayStationContract
     * @param  AirportContract  $airportContract
     * @param  MetroContract  $metroContract
     * @param  OrderEndService  $orderEndService
     * @param  WorkerServiceContract  $workerService
     * @param  GeocodeServiceContract  $geoService
     * @param  RegionServiceContract  $regionService
     */
    public function __construct(
        protected OrderServiceContract $orderService,
        protected DriverServiceContract $driverService,
        protected OrderCrudContract $orderCrudService,
        protected ClientServiceContract $clientService,
        protected ClientCallServiceContract $clientCallService,
        protected CompanyServiceContract $companyService,
        protected FranchiseServiceContract $franchiseService,
        protected DriverCrudContract $driverCrud,
        protected CarServiceContract $carService,
        protected PaymentServiceContract $paymentService,
        protected RailwayStationContract $railwayStationContract,
        protected AirportContract $airportContract,
        protected MetroContract $metroContract,
        protected OrderEndService $orderEndService,
        protected WorkerServiceContract $workerService,
        protected GeocodeServiceContract $geoService,
        protected RegionServiceContract $regionService,
        protected DriverStatusContract $driverStatusContract
    ) {
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $country_code = $this->franchiseService->getFranchisePhoneCode();
        $sub_phone = $this->workerService->getOperatorSubPhone(auth()->user()->system_worker_id);
        $pending_orders = $this->orderService->operatorPendingOrders();
        $order_statuses = $this->orderService->getOrderStatuses();
        $order_types = $this->orderService->getOrderTypes();
        $driver_statuses = $this->driverStatusContract->getDriverStatuses();
        $car_classes = $this->carService->getFranchiseCarClasses(FRANCHISE_ID);
        $car_options = $this->carService->getAllOptions();
        $payment_types = $this->paymentService->getPaymentTypes();
        $calls = $this->clientCallService->getWorkerCalls($sub_phone ? $sub_phone->number : '', 7);
        $drivers = $this->driverCrud->callCenterGetDrivers();
        [$railway_stations, $airports, $metros] = $this->regionService->getTransports();

        return view(
            'system-worker.call-center.index',
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
            )
        );
    }

    /**
     * @param  Request  $request
     * @return LengthAwarePaginator
     */
    public function paginate(Request $request): LengthAwarePaginator
    {
        return $this->orderService->callCenterOrderPaginate($request);
    }

    /**
     * @param  CallCenterAddressByCordsRequest  $request
     * @return Application|ResponseFactory|Response
     * @Get('get-coordinates/{address}', name='call_center_get_coordinates')
     */
    public function getCoordinates(CallCenterAddressByCordsRequest $request): Response|Application|ResponseFactory
    {
        $geoObject = $this->geoService->getCordsByAddress($request->address);

        if (!$geoObject) {
            return response(['message' => 'Error'], 400);
        }

        return response(['coords' => $geoObject]);
    }

    /**
     * @param  CallCenterGetCallsRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function getCalls(CallCenterGetCallsRequest $request): Response|Application|ResponseFactory
    {
        $calls = $this->clientCallService->getWorkerCalls($request->subPhone, 7);

        return $calls
            ? response($calls)
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  CallCenterOrderCreateRequest  $request
     * @return ResponseFactory|Response
     */
    public function orderCreate(CallCenterOrderCreateRequest $request): Response|ResponseFactory
    {
        return $this->orderCrudService->createCallCenterOrder($request->validated())
            ? response(['message' => trans('messages.order_created')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function getDrivers(): Response|Application|ResponseFactory
    {
        return response($this->driverCrud->callCenterGetDrivers());
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function getPendingOrders(): Response|Application|ResponseFactory
    {
        return response($this->orderService->operatorPendingOrders());
    }

    /**
     * @param  CallCenterCreateClientRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function createClient(CallCenterCreateClientRequest $request): Response|Application|ResponseFactory
    {
        $client = $this->clientService->createClientCallCenter($request->all());

        return $client
            ? response(['message' => trans('messages.client_created'), 'client' => $client])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param $client_id
     * @param  CallCenterUpdateClientRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function updateClient($client_id, CallCenterUpdateClientRequest $request): Response|Application|ResponseFactory
    {
        $client = $this->clientService->updateClientCallCenter($client_id, $request->all());

        return $client
            ? response(['message' => trans('messages.client_updated'), 'client' => $client])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  CallCenterUpdateAtcLoggedRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function updateAtcLogged(CallCenterUpdateAtcLoggedRequest $request): Response|Application|ResponseFactory
    {
        return $this->clientCallService->atcLogged($request)
            ? response(['logged' => $request->logged])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  Request  $request
     * @return ResponseFactory|Response
     */
    public function checkClientExists(Request $request): Response|ResponseFactory
    {
        return response($this->clientService->callCenterCheckClientExists($request->phone));
    }

    /**
     * @param  Request  $request
     * @return ResponseFactory|Response
     */
    public function checkPassengerExists(Request $request): Response|ResponseFactory
    {
        return response($this->clientService->callCenterCheckPassengerExists($request->phone));
    }

    /**
     * @param  CallCenterConnectWorkerRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function connectWorker(CallCenterConnectWorkerRequest $request): Response|Application|ResponseFactory
    {
        $values = $this->clientCallService->connectWorker($request->cellNumber, $request->subPhone);
        return $values ?
            response($values) :
            response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  CallCenterCallStartRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function callStart(CallCenterCallStartRequest $request): Response|Application|ResponseFactory
    {
        $call = $this->clientCallService->callStart($request->cellNumber, $request->subPhone);
        return $call ?
            response(['message' => 'ok', 'call' => $call]) :
            response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  CallCenterCallAnsweredRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function callAnswered(CallCenterCallAnsweredRequest $request): Response|Application|ResponseFactory
    {
        return $this->clientCallService->callAnswered($request->call_id) ?
            response(['message' => 'ok']) :
            response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  CallCenterCallEndRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function callEnd(CallCenterCallEndRequest $request): Response|Application|ResponseFactory
    {
        return $this->clientCallService->callEnd($request->cellNumber, $request->subPhone) ?
            response(['message' => 'ok']) :
            response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @Post("call-center/driver-add-order")
     *
     * @param  Request  $request
     */
    public function driverAddOrder(Request $request): void
    {
        $this->orderCrudService->orderAttachToDriver($request->order_id, $request->driver_id);
    }

    /**
     * @param  CoordinatesByAddressRequest  $request
     * @return Application|ResponseFactory|Response
     * @Get('get-coordinates-address/{lat}/{lut}', name='call_center_coordinates_address')
     */
    public function coordinatesAddress(CoordinatesByAddressRequest $request): Response|Application|ResponseFactory
    {
        $geoObject = $this->geoService->getAddressByCords($request->lat, $request->lut);

        if (!$geoObject) {
            return response(['message' => 'error'], 400);
        }

        return response(['address' => null]);
    }

    /**
     * @param $client_id
     * @return Application|ResponseFactory|Response
     */
    public function clientCompanies($client_id): Response|Application|ResponseFactory
    {
        return response($this->clientService->getClientCompanies($client_id));
    }

    /**
     * @param  CallCenterFindCompaniesRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function findCompanies(CallCenterFindCompaniesRequest $request): Response|Application|ResponseFactory
    {
        return response($this->companyService->findCompaniesByCode($request->code, FRANCHISE_ID));
    }

    /**
     * @param  CallCenterGetOrderPriceRequest  $request
     * @return Application|ResponseFactory|AnonymousResourceCollection|Response
     */
    public function getOrderPrice(CallCenterGetOrderPriceRequest $request): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        $options = [
            'payment_type' => (int)$request->payment['type'],
            'payment_type_company' => (int)$request->payment['company'],
            'car_class' => (int)$request->car['class'],
            'demands' => $request->car['options'],
            'rent_time' => $request->validated()['rent_time'] ?? null
        ];

        $route = [
            'from' => [(float)$request->route['from'][0], (float)$request->route['from'][1]],
            'to' => isset($request->route['to'][0]) ? [$request->route['to'][0], $request->route['to'][1]] : []
        ];

        $client = $this->clientService->getClientById($request->client_id, ['client_id', 'phone']);
        $price = $this->orderService->orderFromToPrices($client, $route, $options, $request->time, $request->validated()['is_rent']);

        if (!$price) {
            return response(['message' => 'error data'], 500);
        }

        redis()->hset(ConstRedis::order_calc_request($client->{$client->getKeyName()}), 'init_coin', igbinary_serialize($request->validated()));
        redis()->expire(ConstRedis::order_calc_request($client->{$client->getKeyName()}), 7200 * 60);

        return InitCoinResource::collection($price);
    }

    /**
     * @param $order_id
     * @return Application|ResponseFactory|Response|OrderHistoryResource
     */
    public function getOrderHistory($order_id): Response|OrderHistoryResource|Application|ResponseFactory
    {
        $history = $this->orderService->orderHistory($order_id);

        if (!$history) {
            return response(['message' => 'FAILED'], 500);
        }

        return new OrderHistoryResource($history);
    }

    /**
     * @param  CallCenterSlipUpdateRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function slipUpdate(CallCenterSlipUpdateRequest $request): Response|Application|ResponseFactory
    {
        return $this->orderService->slipUpdate($request)

            ? response(['message' => trans('messages.slip_saved')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  CallCenterCancelOrderRequest  $request
     * @param $order_id
     * @return Application|ResponseFactory|Response
     * @throws Exception
     */
    public function cancelOrder(CallCenterCancelOrderRequest $request, $order_id): Response|Application|ResponseFactory
    {
        return $this->orderEndService->callCenterCancelOrder($order_id)

            ? response(['message' => trans('messages.order_canceled')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  CallCenterOrderCommentCreateRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function orderCommentCreate(CallCenterOrderCommentCreateRequest $request): Response|Application|ResponseFactory
    {
        $comments = $this->orderService->orderCommentCreate($request->order_id, $request->text, $request->for_driver);

        return $comments
            ? response($comments)
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function orderFeedbackWorkers(): Response|Application|ResponseFactory
    {
        $workers = $this->workerService->getCallCenterWorkers(FRANCHISE_ID);

        return $workers
            ? response($workers)
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  CallCenterOrderFeedbackCreateRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function orderFeedbackCreate(CallCenterOrderFeedbackCreateRequest $request): Response|Application|ResponseFactory
    {
        $data = $this->orderService->callCenterOrderFeedbackCreate($request);

        return $data
            ? response(['message' => trans('messages.order_feedback_created'), 'data' => $data])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  SendDriverNotificationRequest  $request
     * @return Response|Application|ResponseFactory
     */
    public function sendDriverNotification(SendDriverNotificationRequest $request): Response|Application|ResponseFactory
    {
        SendNotifies
            ::dispatch($request->clients, 'driver', get_user_id(), $request->title ?? '', $request->text ?? '', $request->image ?? '')
            ->onQueue(ConstQueue::BASE()->getValue());

        return response(['message' => 'ok']);
    }

    /**
     * @param  SendClientNotificationRequest  $request
     * @return Response|Application|ResponseFactory
     */
    public function sendClientNotification(SendClientNotificationRequest $request): Response|Application|ResponseFactory
    {
        SendNotifies
            ::dispatch($request->clients, 'client', get_user_id(), $request->title ?? '', $request->text ?? '', $request->image ?? '')
            ->onQueue(ConstQueue::BASE()->getValue());

        return response(['message' => 'ok']);
    }

    /**
     * @param  int  $order_id
     * @param  int|null  $driver_id
     * @param  string|null  $date
     * @param  bool  $now
     * @return Application|ResponseFactory|Response
     */
    public function changePreorder(int $order_id, int $driver_id = null, string $date = null, bool $now = false)
    {
        $result = $this->workerService->changePreorderData($order_id, $date, $now, $driver_id);

        if (!$result) {
            return response(['message' => 'Invalid'], 500);
        }

        return response(['message' => 'Order changed']);
    }

    /**
     * @param  OrderSendDriverListRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function sendList(OrderSendDriverListRequest $request)
    {
        $result = $this->workerService->sendOrderToDriversList($request->order_id, $request->drivers, $request->radius, $request->type);

        if (!$result) {
            return response(['message' => 'Error'], 500);
        }

        return response(['message' => 'Sended common list']);
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|AnonymousResourceCollection|Response
     */
    public function driverForEdit(Request $request)
    {
        if (!$request->radius && !$request->type) {
            return response(['message' => 'ok', 'payload' => $this->workerService->getDriverForEditFilters((int)$request->order_id)]);
        }

        $drivers = $this->workerService->getDriverForEditOrder((int)$request->order_id, (int)$request->radius, (int)$request->type);

        return DriverByTypeDistanceResource::collection($drivers);
    }
}
