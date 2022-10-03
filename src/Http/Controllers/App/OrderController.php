<?php

declare(strict_types=1);

namespace Src\Http\Controllers\App;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Src\Core\Enums\ConstRedis;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\Client\AddFeedbackAbortedOrderRequest;
use Src\Http\Requests\Client\CancelOrderAcceptRequest;
use Src\Http\Requests\Client\ClientOrderCreateRequest;
use Src\Http\Requests\Client\GetOrderPriceRequest;
use Src\Http\Requests\Client\OrderCancelRequest;
use Src\Http\Resources\App\InitCoinResource;
use Src\Http\Resources\App\OrderCancelResourcse;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Client\GetPreOrdersResource;
use Src\Http\Resources\Client\OrderHistoryResource;
use Src\Http\Resources\PaginateResource;
use Src\Repositories\InitialOrderData\InitialOrderDataContract;
use Src\Services\Car\CarServiceContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Company\CompanyServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\OrderEnd\OrderEndServiceContract;
use Src\ServicesCrud\Order\OrderCrudContract;

/**
 * Class OrderController
 * @package Src\Http\Controllers\ClientWeb
 */
class OrderController extends Controller
{
    /**
     * CorporateClientController constructor.
     * @param  OrderServiceContract  $orderService
     * @param  OrderEndServiceContract  $orderRejectService
     * @param  ClientServiceContract  $clientService
     * @param  CompanyServiceContract  $companyService
     * @param  OrderCrudContract  $orderCrud
     * @param  CarServiceContract  $carService
     * @param  InitialOrderDataContract  $initialOrderContract
     */
    public function __construct(
        protected OrderServiceContract $orderService,
        protected OrderEndServiceContract $orderRejectService,
        protected ClientServiceContract $clientService,
        protected CompanyServiceContract $companyService,
        protected OrderCrudContract $orderCrud,
        protected CarServiceContract $carService,
        protected InitialOrderDataContract $initialOrderContract
    ) {
    }

    /**
     * @param  OrderCancelRequest  $request
     * @return Response|JsonResponse|ResponseFactory
     */
    public function cancelOrder(OrderCancelRequest $request): Response|JsonResponse|ResponseFactory
    {
        $result = $this->orderRejectService->clientCancelOrder(get_user_id());

        if (!$result) {
            return response(['message' => 'You can already cancel your order'], 422);
        }

        return (new OrderCancelResourcse($result))
            ->additional(['message' => $result['cancel_price'] ? 'Canceled Price' : 'Canceled'])
            ->response()
            ->setStatusCode($result['cancel_price'] ? 201 : 200);
    }

    /**
     * @param  CancelOrderAcceptRequest  $request
     * @return JsonResponse|Application|ResponseFactory
     */
    public function cancelOrderAccept(CancelOrderAcceptRequest $request): JsonResponse|Application|ResponseFactory
    {
        $result = $this->orderRejectService->clientAcceptCancel(get_user_id(), $request->accept);

        if (!$result) {
            return response(['message' => 'error'], 500);
        }

        return (new OrderCancelResourcse($result))
            ->additional(['message' => $result['cancel_price'] ? 'Canceled Price' : 'Canceled'])
            ->response()
            ->setStatusCode($result['cancel_price'] ? 201 : 200);
    }

    /**
     * @param  AddFeedbackAbortedOrderRequest  $request
     * @return ResponseFactory|Response
     */
    public function addFeedbackOrder(AddFeedbackAbortedOrderRequest $request): Response|ResponseFactory
    {
        $data = $request->validated();

        if (isset($data['completed_order_id'], $data['feedback']['assessment'])) {
            $feedback = $this->orderRejectService->clientCompletedFeedback(
                user()->{user()->getKeyName()},
                $data['completed_order_id'],
                $data['feedback']
            );
        } else {
            $feedback = $this->orderRejectService->createClientAbortedFeedback(
                $request->user()->{$request->user()->getKeyName()},
                $request->aborted_order_id,
                $request->feedback
            );
        }

        if (!$feedback) {
            return response(['message' => 'SERVER ERROR', 'status' => 'FAIL'], 500);
        }

        return response(['message' => 'Feedback Added']);
    }

    /**
     * @param  int  $order_id
     * @return Application|ResponseFactory|Response
     */
    public function continueOrder(int $order_id)
    {
        $result = $this->orderService->setRepeatedAt($order_id);

        return $result
            ? response(['message' => 'Order updated', 'repeated_at' => $result['repeated_at']], 200)
            : response(['message' => 'SERVER ERROR'], 500);
    }

    /**
     * @param $assessment
     * @return Application|ResponseFactory|Response
     */
    public function assessmentDetails($assessment): Response|Application|ResponseFactory
    {
        $result = $this->orderRejectService->getFeedbackByAssessment((int)$assessment);

        return response(['message' => 'OK', '_payload' => $result]);
    }

    /**
     * @param  ClientOrderCreateRequest  $request
     * @return ResponseFactory|Response
     * @noinspection MultipleReturnStatementsInspection
     */
    public function createOrder(ClientOrderCreateRequest $request): Response|ResponseFactory
    {
        if (!$request->validated()) {
            return response(['message' => 'SERVER ERROR'], 500);
        }

        $output = $this->orderCrud->compareTimeWithPreOrderTime($request['time']);

        if (!$output['status']) {
            return response(['message' => $output['message']], 400);
        }

        $data = redis()->hget(ConstRedis::order_calc_request(get_user_id()), 'init_coin');

        if ($data) {
            $coin_data = igus($data);
            if ($diff = array_diff($request->validated()['car']['options'], $coin_data['car']['options'])) {
                $option_re_price = $this->carService->calculateOrderOptions($diff);
                $this->initialOrderContract
                    ->where('orderable_id', '=', user()->{user()->getKeyName()})
                    ->where('orderable_type', '=', user()->getMap())
                    ->where('order_id', '=')
                    ->updateSet(['price' => DB::raw('price+'.$option_re_price)]);
            }

            redis()->hdel(ConstRedis::order_calc_request(get_user_id()), 'init_coin');
        }

        $order_data = $this->orderCrud->orderDataFilter($request->validated());
        $result = $this->orderCrud->createOrder($order_data);

        if (!$result) {
            return response('Something went wrong', 400);
        }

        if (!$result->get('schedule')) {
            return response(['message' => 'Order created successful', 'data' => $result->get('price'), 'order_id' => $result['order']['order_id']], 200);
        }

        return response(['message' => 'Ваш Заказ оформлен', 'data' => $result->get('price'), 'order_id' => $result['order']['order_id']], 201);
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response|BaseResource|PaginateResource
     */
    public function getOrders(Request $request)
    {
        $orders = $this->clientService->getOrders($request->all());

        if (!$orders) {
            return response('Something get wrong', 400);
        }

        return (new PaginateResource($orders))->collectionClass(OrderHistoryResource::class);
    }

    /**
     * @param  GetOrderPriceRequest  $request
     * @return Application|ResponseFactory|AnonymousResourceCollection|Response
     */
    public function getOrderPrice(GetOrderPriceRequest $request): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        $user = user();

        if (!$user) {
            return response(['message' => 'Unauthorized', 'status' => 'FAILED'], 401);
        }

        $options = [
            'payment_type' => $request->validated()['payment']['type'],
            'payment_type_company' => $request->validated()['payment']['company'],
            'car_class' => $request->validated()['car']['class'],
            'time' => $request->validated()['time']['time'],
            'demands' => $request->validated()['car']['options'],
            'rent_time' => $request->validated()['rent_time'] ?? null
        ];

        $price_data = $this->orderService->orderFromToPrices($user, $request->validated()['route'], $options, $request->validated()['time'],
            $request->validated()['is_rent']);

        if (!$price_data) {
            return response(['message' => 'Ошибка Расчета', 'status' => 'FAILED'], 500);
        }

        redis()->hset(ConstRedis::order_calc_request(get_user_id()), 'init_coin', igbinary_serialize($request->validated()));
        redis()->expire(ConstRedis::order_calc_request(get_user_id()), 7200 * 60);

        return InitCoinResource::collection($price_data);
    }

    /**
     * @param  GetOrderPriceRequest  $request
     * @return Application|ResponseFactory|AnonymousResourceCollection|Response
     */
    public function getMobileOrderPrice(GetOrderPriceRequest $request): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        $user = user();

        if (!$user) {
            return response(['message' => 'Ошибка Расчета', 'status' => 'FAILED'], 401);
        }

        $options = [
            'payment_type' => $request->validated()['payment']['type'],
            'payment_type_company' => $request->validated()['payment']['company'],
            'car_class' => $request->validated()['car']['class'],
            'time' => $request->validated()['time']['time'],
            'demands' => $request->validated()['car']['options'],
            'rent_time' => $request->validated()['rent_time'] ?? null
        ];

        $price_data = $this->orderService->orderFromToPrices($user, $request->validated()['route'], $options, $request->validated()['time'],
            $request->validated()['is_rent']);

        if (!$price_data) {
            return response(['message' => 'Ошибка Расчета', 'status' => 'FAILED'], 500);
        }

        redis()->hset(ConstRedis::order_calc_request(get_user_id()), 'init_coin', igbinary_serialize($request->validated()));

        return InitCoinResource::collection($price_data);
    }

    /**
     * @return Application|Factory|View
     */
    public function showPreorders(): View|Factory|Application
    {
        return view('app.mobile.preorders');
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getPreorders(): AnonymousResourceCollection
    {
        $preorders = $this->clientService->getPreOrders(get_user_id());

        return GetPreOrdersResource::collection($preorders)->additional(['message' => 'ok']);
    }

    /**
     * @param $order_id
     * @return Application|ResponseFactory|Response
     */
    public function deletePreorder($order_id): Response|Application|ResponseFactory
    {
        $this->clientService->deletePreorder(get_user_id(), $order_id);

        return response(['message' => 'ok']);
    }
}
