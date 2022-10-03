<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AppMobile;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Src\Core\Enums\ConstRedis;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\ClientMobile\AddFeedbackAbortedOrderRequest;
use Src\Http\Requests\ClientMobile\CancelOrderAcceptRequest;
use Src\Http\Requests\ClientMobile\ClientOrderCreateRequest;
use Src\Http\Requests\ClientMobile\GetOrderDetailRequest;
use Src\Http\Requests\ClientMobile\OrderCancelRequest;
use Src\Http\Resources\App\OrderCancelResourcse;
use Src\Http\Resources\Client\GetPreOrdersResource;
use Src\Http\Resources\ClientMobile\GetOrdersDetailResource;
use Src\Http\Resources\ClientMobile\GetOrdersResource;
use Src\Http\Resources\CreateOrderResource;
use Src\Http\Resources\Driver\GetAssessmentDetailResource;
use Src\Repositories\InitialOrderData\InitialOrderDataContract;
use Src\Services\Car\CarServiceContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\OrderEnd\OrderEndServiceContract;
use Src\ServicesCrud\Order\OrderCrudContract;

/**
 * Class OrderController
 * @package Src\Http\Controllers\AppMobile
 */
class OrderController extends Controller
{
    /**
     * @var ClientServiceContract
     */
    protected ClientServiceContract $clientService;
    /**
     * @var OrderServiceContract
     */
    protected OrderServiceContract $orderService;
    /**
     * @var OrderCrudContract
     */
    protected OrderCrudContract $orderCrud;
    /**
     * @var OrderEndServiceContract
     */
    protected OrderEndServiceContract $orderEndService;
    /**
     * @var OrderEndServiceContract
     */
    protected OrderEndServiceContract $orderRejectService;
    /**
     * @var CarServiceContract
     */
    protected CarServiceContract $carService;
    /**
     * @var InitialOrderDataContract
     */
    protected InitialOrderDataContract $initialOrderContract;

    /**
     * AppController constructor.
     * @param  ClientServiceContract  $clientService
     * @param  OrderServiceContract  $orderService
     * @param  OrderCrudContract  $orderCrud
     * @param  OrderEndServiceContract  $orderReject
     * @param  OrderEndServiceContract  $orderEndService
     * @param  CarServiceContract  $carService
     * @param  InitialOrderDataContract  $initialOrderContract
     */
    public function __construct(
        ClientServiceContract $clientService,
        OrderServiceContract $orderService,
        OrderCrudContract $orderCrud,
        OrderEndServiceContract $orderReject,
        OrderEndServiceContract $orderEndService,
        CarServiceContract $carService,
        InitialOrderDataContract $initialOrderContract
    ) {
        $this->clientService = $clientService;
        $this->orderService = $orderService;
        $this->orderCrud = $orderCrud;
        $this->orderRejectService = $orderReject;
        $this->orderEndService = $orderEndService;
        $this->carService = $carService;
        $this->initialOrderContract = $initialOrderContract;
    }

    /**
     * @param  ClientOrderCreateRequest  $request
     * @return ResponseFactory|Response|CreateOrderResource
     * @noinspection MultipleReturnStatementsInspection
     */
    public function createOrder(ClientOrderCreateRequest $request)
    {
        if (!$request->validated()) {
            return response(['message' => 'SERVER ERROR'], 500);
        }

        $redis = redis();
        $data = $redis->hGet(ConstRedis::order_calc_request(get_user_id()), 'init_coin');

        if ($data) {
            $coin_data = igbinary_unserialize($data);
            if ($diff = array_diff($request->validated()['car']['options'], $coin_data['car']['options'])) {
                $option_re_price = $this->carService->calculateOrderOptions($diff);
                $this->initialOrderContract
                    ->where('orderable_id', '=', user()->{user()->getKeyName()})
                    ->where('orderable_type', '=', user()->getMap())
                    ->where('order_id', '=')
                    ->updateSet(['price' => DB::raw('price+'.$option_re_price)]);
            }

            $redis->hDel(ConstRedis::order_calc_request(get_user_id()), ...['init_coin']);
        }

        $order_data = $this->orderCrud->orderDataFilter($request->validated());
        $result = $this->orderCrud->createOrder($order_data);

        if (!$result) {
            return response('Something went wrong', 400);
        }

        if ($result->get('schedule')) {
            return response(['message' => 'Order created successful', 'data' => $result->get('price')], 201);
        }

        return new CreateOrderResource($result->get('price'));
    }


    /**
     * @param  OrderCancelRequest  $request
     * @return Application|ResponseFactory|JsonResponse|Response|object
     */
    public function cancelOrder(OrderCancelRequest $request)
    {
        $result = $this->orderEndService->clientCancelOrder(get_user_id());

        if (!$result) {
            return response(['message' => 'You can already cancel your order'], 422);
        }

        return (new OrderCancelResourcse($result))
            ->additional(['message' => $result['cancel_price'] ? 'Canceled Price' : 'Canceled'])
            ->response()
            ->setStatusCode($result['cancel_price'] ? 201 : 200);
    }

    /**
     * @return Application|ResponseFactory|JsonResponse|Response|object
     */
    public function cancelOrderAccept(CancelOrderAcceptRequest $request)
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
    public function addFeedbackOrder(AddFeedbackAbortedOrderRequest $request)
    {
        $data = $request->validated();

        if (isset($data['completed_order_id'], $data['feedback']['assessment'])) {
            $feedback = $this->orderEndService->clientCompletedFeedback(
                user()->{user()->getKeyName()},
                $data['completed_order_id'],
                $data['feedback'],
                $data['favorite']
            );
        } else {
            $feedback = $this->orderEndService->createClientAbortedFeedback(
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
     * @param  null  $assessment
     * @return AnonymousResourceCollection
     */
    public function assessmentDetails($assessment = null): AnonymousResourceCollection
    {
        $result = $this->orderRejectService->getFeedbackByAssessment((int)$assessment);

        return GetAssessmentDetailResource::collection($result);
    }

    /**
     * @param  Request  $request
     * @param  int  $skip
     * @param  int  $take
     * @return AnonymousResourceCollection
     */
    public function getOrders(Request $request, int $skip = 0, int $take = 3): AnonymousResourceCollection
    {
        $orders = $this->clientService->getClientMobileOrders(get_user_id(), $skip, $take);

        return GetOrdersResource::collection($orders);
    }

    /**
     * @param  GetOrderDetailRequest  $detailRequest
     * @param $order_id
     * @return GetOrdersDetailResource
     */
    public function orderDetail(GetOrderDetailRequest $detailRequest, $order_id): GetOrdersDetailResource
    {
        $detail = $this->clientService->getOrderDetail($order_id, get_user_id());

        return new GetOrdersDetailResource($detail);
    }

    /**
     * @param  null  $skip
     * @param  null  $take
     * @return AnonymousResourceCollection
     */
    public function getPreorders($skip = null, $take = null): AnonymousResourceCollection
    {
        $preorders = $this->clientService->getPreOrders(get_user_id(), $skip, $take);

        return GetPreOrdersResource::collection($preorders);
    }

    /**
     * @param $order_id
     * @return Application|ResponseFactory|Response
     */
    public function deletePreorder($order_id)
    {
        $delete = $this->clientService->deletePreorder(get_user_id(), $order_id);

        if (!$delete) {
            return response(['message' => 'Error'], 400);
        }

        return response(['message' => 'deleted', 'status' => 'OK']);
    }
}
