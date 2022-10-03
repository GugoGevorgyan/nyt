<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminCorporate;

use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Collective\Annotations\Routing\Annotations\Annotations\Post;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Src\Core\Enums\ConstRedis;
use Src\Core\Enums\ConstRentTimes;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\AdminCorporate\CancelOrderRequest;
use Src\Http\Requests\AdminCorporate\CompanyOrderCreateRequest;
use Src\Http\Requests\AdminCorporate\GenerateExcelRequest;
use Src\Http\Requests\AdminCorporate\InitCoinRequest;
use Src\Http\Requests\AdminCorporate\PrintExcelRequest;
use Src\Http\Requests\AdminCorporate\ScheduledCorporateOrdersRequest;
use Src\Http\Resources\AdminCorporate\OpenOrderResource;
use Src\Http\Resources\AdminCorporate\OrderHistoryResource;
use Src\Http\Resources\App\InitCoinResource;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\PaginateResource;
use Src\Services\Car\CarServiceContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Company\CompanyServiceContract;
use Src\Services\CorporateClient\CorporateClientServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\Tariff\TariffServiceContract;
use Src\ServicesCrud\Order\OrderCrudContract;
use Storage;

/**
 * Class OrderController
 * @package Src\Http\Controllers\AdminCorporate
 */
class OrderController extends Controller
{
    /**
     * CorporateClientController constructor.
     * @param  OrderCrudContract  $orderCrudService
     * @param  CompanyServiceContract  $companyService
     * @param  OrderServiceContract  $orderService
     * @param  CarServiceContract  $carService
     * @param  ClientServiceContract  $clientService
     * @param  CorporateClientServiceContract  $corporateClientContract
     * @param  TariffServiceContract  $tariffService
     */
    public function __construct(
        protected OrderCrudContract $orderCrudService,
        protected CompanyServiceContract $companyService,
        protected OrderServiceContract $orderService,
        protected CarServiceContract $carService,
        protected ClientServiceContract $clientService,
        protected CorporateClientServiceContract $corporateClientContract,
        protected TariffServiceContract $tariffService
    ) {
    }

    /**
     * @Post('company/clients/order-info/', as='createOrderCorporate', no_prefix='true')
     * @param $id
     * @return Application|ResponseFactory|Response
     */
    public function getOrderInfo($id): Response|Application|ResponseFactory
    {
        $orderInfo = $this->orderService->getOrderInfo($id);

        if (!$orderInfo) {
            return response('Something went wrong', 500);
        }

        return response($orderInfo, 200);
    }

    /**
     * @Post("company/order/create", as="createOrderCorporate", no_prefix="true")
     *
     * @param  CompanyOrderCreateRequest  $request
     * @return Application|Response|ResponseFactory
     */
    public function createCorporateOrder(CompanyOrderCreateRequest $request): Response|Application|ResponseFactory
    {
        $order = $this->orderCrudService->companyClientOrderFilter($request->validated());
        $create = $this->orderCrudService->createOrder($order);

        if ($create) {
            return response(['message' => 'Order created successful']);
        }

        return response('Something get wrong', 400);
    }

    /**
     * @Post("company/clients/scheduled-orders", as="scheduledCorporateOrders", no_prefix="true")
     *
     * @param  ScheduledCorporateOrdersRequest  $request
     * @return ResponseFactory|Response
     */
    public function scheduledCorporateOrders(ScheduledCorporateOrdersRequest $request): Response|ResponseFactory
    {
        $order = $this->orderService->scheduledCorporateOrders($request->all());

        if (!$order) {
            return response('Something get wrong', 400);
        }

        return response('Orders created successful');
    }

    /**
     * @Get("order/paginate", as="getCompanyOrders", no_prefix="true")
     *
     * @param  Request  $request
     * @return Application|PaginateResource|Response|ResponseFactory|BaseResource
     */
    public function getCompanyOrders(Request $request): Response|PaginateResource|BaseResource|Application|ResponseFactory
    {
        $result_orders = $this->companyService->getOrdersPaginate($request);

        if (!$result_orders || !$result_orders->count()) {
            return response(['message' => 'Error'], 500);
        }

        return (new PaginateResource($result_orders))->collectionClass(OrderHistoryResource::class);
    }

    /**
     * @param  Request  $request
     * @return OpenOrderResource
     */
    public function openOrderModal(Request $request): OpenOrderResource
    {
        $data = [
            'client_phone' => $request->client_phone['client'],
            'company_id' => $request->company_id
        ];

        $client = $this->clientService->getClientByPhone($data['client_phone'], ['client_id', 'phone']);
        $check_limit = $this->orderService->checkClientLimit($data);
        $car_options = $this->orderService->getCarOptions();
        $payment_types = $this->orderService->getPaymentTypes();
        $car_classes = $this->carService->carClassByCompany(user()->company_id, $client->client_id);
        $rent_times = $car_classes ? $this->tariffService->getRentTimesByData($car_classes[0]) : array_values(ConstRentTimes::getAll());

        $resource = compact('car_options', 'car_classes', 'payment_types', 'rent_times', 'check_limit');

        return new OpenOrderResource($resource);
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function closeOrderModal(Request $request): Response|Application|ResponseFactory
    {
        $this->companyService->closeOrderDialog($request->client_phone, $request->company_id);

        return response(['message' => 'ok']);
    }

    /**
     * @param  InitCoinRequest  $request
     * @return Application|ResponseFactory|AnonymousResourceCollection|Response
     */
    public function initCoin(InitCoinRequest $request): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        $options = [
            'payment_type' => (int)$request->payment['type'],
            'payment_type_company' => (int)$request->payment['company'],
            'car_class' => (int)$request->car['class'],
            'demands' => $request->car['options'] ?? [],
            'time' => $request->validated()['time']['time'],
            'rent_time' => $request->validated()['rent_time'] ?? null
        ];

        $route = [
            'from' => [$request->route['from'][0], $request->route['from'][1]],
            'to' => !empty($request->route['to']) ? [$request->route['to'][0], $request->route['to'][1]] : []
        ];

        $client = $this->clientService->getClientByPhone($request['phone']['client']);
        $price_data = $this->orderService->orderFromToPrices($client, $route, $options, $request->time, $request->is_rent);

        if (!$price_data) {
            return response(['message' => 'Failed'], 400);
        }

        redis()->hset(ConstRedis::order_calc_request($client->{$client->getKeyName()}), 'init_coin', igbinary_serialize($request->validated()));
        redis()->expire(ConstRedis::order_calc_request($client->{$client->getKeyName()}), 7200 * 60);

        return InitCoinResource::collection($price_data);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function getCities(): Response|Application|ResponseFactory
    {
        $cities = $this->companyService->getCities();

        if (!$cities) {
            return response(['message' => 'failed'], 400);
        }

        return response(['message' => 'ok', '_payload' => $cities]);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function getAirports(): Response|Application|ResponseFactory
    {
        $city = $this->companyService->getCompanyCities(user()->{user()->getKeyName()});
        $airports[] = $city->franchise_cities->flatMap(fn($item) => $this->companyService->getAirports($item->city_id));

        if (!$airports) {
            return response(['message' => 'Not Detected Airports'], 400);
        }

        return response(['message' => 'OK', '_payload' => $airports[0]]);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function getStations(): Response|Application|ResponseFactory
    {
        $city = $this->companyService->getCompanyCities(user()->{user()->getKeyName()});
        $stations[] = $city->franchise_cities->flatMap(fn($item) => $this->companyService->getStations($item->city_id));

        if (!$stations) {
            return response(['message' => 'Not Detected Airports'], 400);
        }

        return response(['message' => 'OK', '_payload' => $stations[0]]);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function getMetros(): Response|Application|ResponseFactory
    {
        $city = $this->companyService->getCompanyCities(user()->{user()->getKeyName()});
        $metros[] = $city->franchise_cities->flatMap(fn($item) => $this->companyService->getMetros($item->city_id));

        if (!$metros) {
            return response(['message' => 'Not Detected Airports'], 400);
        }

        return response(['message' => 'OK', '_payload' => $metros[0]]);
    }

    /**
     * @param  GenerateExcelRequest  $request
     * @return void
     * @throws FileNotFoundException
     */
    public function generateExcel(GenerateExcelRequest $request): void
    {
        $excel = $this->companyService->generateOrderExcel($request);

        file_put_contents('php://output', Storage::disk('local')->get(str_replace('app', '', $excel->get('path')).$excel->get('name')));
    }

    /**
     * @param  PrintExcelRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function printExcel(PrintExcelRequest $request): Response|Application|ResponseFactory
    {
        $print_data = $this->companyService->printExcelData($request);

        return response(['message' => 'ok', '_payload' => $print_data]);
    }

    /**
     * @param  CancelOrderRequest  $request
     * @param  string  $order_id
     * @param  string  $client_id
     * @return Application|ResponseFactory|Response
     */
    public function cancelOrder(CancelOrderRequest $request, string $order_id, string $client_id): Response|Application|ResponseFactory
    {
        if (!$request->password || !Hash::check($request->password, user()->password)) {
            return response(['message' => 'Invalid password'], 422);
        }

        $result = $this->companyService->cancelOrder($order_id, $client_id);

        if (!$result) {
            return response(['message' => 'error with canceled order'], 400);
        }

        return response(['message' => 'canceled']);
    }
}
