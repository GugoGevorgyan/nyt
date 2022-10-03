<?php

declare(strict_types=1);

namespace Src\Http\Controllers\DriverApi;

use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Src\Broadcasting\Broadcast\Client\DriverInPlace;
use Src\Broadcasting\Broadcast\Client\OrderStarted;
use Src\Broadcasting\Notifications\ClientNotify;
use Src\Core\Enums\ConstQueue;
use Src\Core\Enums\ConstRatingPattern;
use Src\Exceptions\Lexcept;
use Src\Exceptions\UnauthorizedException;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\Driver\CommonOrderRequest;
use Src\Http\Requests\Driver\DriverrInPlaceRequest;
use Src\Http\Requests\Driver\GetDayOrdersRequest;
use Src\Http\Requests\Driver\GetOrderRejectOptionsRequest;
use Src\Http\Requests\Driver\OrderAddFeedbackRequest;
use Src\Http\Requests\Driver\OrderLateRequest;
use Src\Http\Requests\Driver\OrderOnWayRequest;
use Src\Http\Requests\Driver\OrderRejectRequest;
use Src\Http\Requests\Driver\PrepareCommonOrderRequest;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Driver\DriverGotToOrderResource;
use Src\Http\Resources\Driver\DriverMapViewResource;
use Src\Http\Resources\Driver\DriverOrderEndResource;
use Src\Http\Resources\Driver\GetAssessmentResource;
use Src\Http\Resources\Driver\GetDayOrdersResource;
use Src\Http\Resources\Driver\OrderInPlaceResource;
use Src\Http\Resources\Driver\OrderListResource;
use Src\Http\Resources\Driver\OrderListTrajectoryResource;
use Src\Http\Resources\Driver\OrderOnStartResource;
use Src\Http\Resources\Driver\OrderRejectOptionsResource;
use Src\Http\Resources\Driver\OrderRejectResource;
use Src\Http\Resources\Driver\OrderSelectRouteResource;
use Src\Http\Resources\Driver\OrderShippedResource;
use Src\Http\Resources\Driver\PassCommonOrderResource;
use Src\Http\Resources\Driver\PassOrderResource;
use Src\Jobs\OrderCommons\TimerWaitingClient;
use Src\Support\Rules\Driver\DriverOnStartSelectRouteRule;
use Src\Support\Rules\Driver\DriverOrderEndRule;
use Src\Support\Rules\Driver\DriverOrderOnStartRule;
use Src\Support\Rules\Driver\DriverOrderShippedRule;
use Src\Services\Client\ClientService;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\OrderEnd\OrderEndServiceContract;
use Src\Services\OrderFeedback\OrderFeedbackServiceContract;
use Src\Services\RatingPointService\RatingPointServiceContract;
use Src\Services\Worker\WorkerServiceContract;
use Src\ServicesCrud\Order\OrderCrudContract;
use Src\Support\Facades\Sms;

/**
 * Class OrderController
 * @package Src\Http\Controllers\ApiDriver
 */
class OrderController extends Controller
{
    /**
     * OrderController constructor.
     * @param  OrderServiceContract  $orderService
     * @param  DriverServiceContract  $driverService
     * @param  ClientService  $clientService
     * @param  OrderEndServiceContract  $orderEndService
     * @param  OrderCrudContract  $orderCrud
     * @param  WorkerServiceContract  $workerService
     * @param  OrderFeedbackServiceContract  $feedbackService
     * @param  RatingPointServiceContract  $ratingService
     */
    public function __construct(
        protected OrderServiceContract $orderService,
        protected DriverServiceContract $driverService,
        protected ClientService $clientService,
        protected OrderEndServiceContract $orderEndService,
        protected OrderCrudContract $orderCrud,
        protected WorkerServiceContract $workerService,
        protected OrderFeedbackServiceContract $feedbackService,
        protected RatingPointServiceContract $ratingService
    ) {
    }

    /**
     * @Get("/order_acceptance/{hash}/{response}")
     *
     * @param  DriverOrderShippedRule  $validate
     * @param $order_id
     * @param $hash
     * @param  bool|null  $accept
     * @return Response|OrderShippedResource|JsonResource|ResponseFactory
     * @noinspection MultipleReturnStatementsInspection
     */
    public function responseShippedOrder(
        DriverOrderShippedRule $validate,
        $order_id,
        $hash,
        bool $accept = true
    ): Response|OrderShippedResource|JsonResource|ResponseFactory {
        $driver = user();

        if (!$driver || !$validate->passes('accept_hash', compact('hash', 'order_id'))) {
            return response(['message' => $validate->message()], 422);
        }

        $result = $this->driverService->driverShippedOrder($hash, $order_id, user()->{user()->getKeyName()}, $accept);

        if (!$result) {
            return response(['message' => 'Server Error'], 500);
        }

        return new OrderShippedResource($result);
    }

    /**
     * @Get("/order_on_way/{hash}/{selected_route}")
     *
     * @param  OrderOnWayRequest  $request
     * @return DriverGotToOrderResource|Response|JsonResource|ResponseFactory
     * @noinspection MultipleReturnStatementsInspection
     */
    public function responseGoToOrder(OrderOnWayRequest $request): DriverGotToOrderResource|Response|JsonResource|ResponseFactory
    {
        $result = $this->driverService->driverOnWay($request->order_id, $request->hash, get_user_id(), $request->selected_route, $request->accept);

        if (!$result) {
            return response(['message' => 'SERVER ERROR'], 500);
        }

        return new DriverGotToOrderResource($result);
    }

    /**
     * @Get("/order_in_place/{order_id}/{hash}/{place_lat}|{place_lut}", name="driver.response.in_place.order")
     *
     * @param  DriverrInPlaceRequest  $request
     * @param $order_id
     * @param $hash
     * @return Response|OrderInPlaceResource|JsonResource|ResponseFactory
     * @throws UnauthorizedException
     */
    public function responseInPlaceOrder(DriverrInPlaceRequest $request, $order_id, $hash): Response|OrderInPlaceResource|JsonResource|ResponseFactory
    {
        $driver = user();

        if (!$driver) {
            throw new UnauthorizedException('Unauthorized');
        }

        $lat = $driver->lat;
        $lut = $driver->lut;

        $routes_data = $this->driverService->driverInPlace($order_id, $hash, compact('lat', 'lut'));
        $client = $this->clientService->getOrderedClientData($order_id, ['client_id', 'phone']);
        $driver_info = $this->driverService->getDriverUpdatedDriverData($driver->driver_id);

        if ($routes_data['paid_wait_minute']) {
            TimerWaitingClient::dispatch($client, $driver, $routes_data['paid_wait_minute'])
                ->delay(now()->addSeconds((int)round($routes_data['free_wait_minute'] * 60)))
                ->onQueue(ConstQueue::LONG()->getValue());
        }

        $tariff_features = [
            'paid_wait_minute' => $routes_data['paid_wait_minute'],
            'free_wait_minute' => $routes_data['free_wait_minute'],
        ];
        $driver['name'] = $driver_info['name'];
        $driver['surname'] = $driver_info['surname'];
        $driver['photo'] = $driver_info['photo'];

        DriverInPlace::broadcast($client, new DriverMapViewResource($driver), trans('messages.driver_in_place'), $tariff_features);
        Notification::send($client, new ClientNotify((array)$driver));
        $client ? Sms::send($client->phone, trans('messages.client_car_waiting', [
            'car_mark' => $driver_info['car']['mark'],
            'car_model' => $driver_info['car']['model'],
            'driver_name' => $driver_info['name'],
            'driver_phone' => $driver_info['phone']
        ])) : null;

        return !$routes_data ? response(['message' => 'Server Error'], 500) : new OrderInPlaceResource($routes_data);
    }

    /**
     * @param  DriverOnStartSelectRouteRule  $validate
     * @param $order_id
     * @param $hash
     * @param $selected_route
     * @return OrderSelectRouteResource|Response|JsonResource|ResponseFactory
     */
    public function responseInStartSelectRoute(
        DriverOnStartSelectRouteRule $validate,
        $order_id,
        $hash,
        $selected_route
    ): OrderSelectRouteResource|Response|JsonResource|ResponseFactory {
        if (!$validate->passes('in_order_hash', compact('order_id', 'hash', 'selected_route'))) {
            return response(['message' => $validate->message()], 422);
        }

        $data = $this->driverService->responseInStartSelectedRoute($order_id, $selected_route);

        if (!$data) {
            return response(['message' => 'Server Error'], 500);
        }

        return new OrderSelectRouteResource($data);
    }

    /**
     * @Get("/order_on_start/{order_id}/{hash}/{from_lat}|{from_lut}/{selected_route_or_lat}|{lut}", name="driver.response.on_start.order")
     *
     * @param  DriverOrderOnStartRule  $validate
     * @param $order_id
     * @param $hash
     * @param  null  $route_or_to_lat
     * @param  null  $to_lut
     * @return OrderOnStartResource|BaseResource|ResponseFactory
     */
    public function responseInStartOrder(
        DriverOrderOnStartRule $validate,
        $order_id,
        $hash,
        $route_or_to_lat = null,
        $to_lut = null
    ): OrderOnStartResource|BaseResource|ResponseFactory {
        $driver = user();

        if (!$driver || !$validate->passes('in_order_hash', compact('order_id', 'hash', 'route_or_to_lat', 'to_lut'))) {
            return response(['message' => $validate->message()], 422);
        }

        $result = $this->driverService->responseInStartOrder($order_id, $hash, get_user_id(), $route_or_to_lat, $to_lut);

        if (!$result) {
            return response(['message' => 'Server Error'], 500);
        }

        $result->get('client') ? OrderStarted::broadcast($result->get('client')) : null;

        return new OrderOnStartResource($result);
    }

    /**
     * @Get("/order_on_end/{hash}/{response}")
     *
     * @param  DriverOrderEndRule  $validation
     * @param $order_id
     * @param $hash
     * @return Response|JsonResource|DriverOrderEndResource|ResponseFactory
     */
    public function responseOrderOnEnd(DriverOrderEndRule $validation, $order_id, $hash): Response|JsonResource|DriverOrderEndResource|ResponseFactory
    {
        $driver = user();

        if (!$driver && !$validation->passes('end_hash', compact('hash', 'order_id'))) {
            return response(['message' => $validation->message()], 422);
        }

        $lat = $driver->lat;
        $lut = $driver->lut;

        $result = $this->driverService->orderEnd($order_id, $hash, compact('lat', 'lut'));

        if (!$result) {
            return response(['message' => 'SERVER ERROR'], 500);
        }

        $client = $this->clientService->getOrderedClientData($order_id, ['client_id', 'phone']);
        $driver = $this->orderService->getOrderDriver($order_id);

        Notification::send($client, new ClientNotify([$driver]));

        return new DriverOrderEndResource($result);
    }

    /**
     * @param  int  $order_id
     * @param  int  $assessment
     * @return Application|ResponseFactory|Response|GetAssessmentResource
     */
    public function getAssessmentType(int $order_id, int $assessment): GetAssessmentResource|Response|Application|ResponseFactory
    {
        $feedback = $this->orderEndService->getFeedbackByAssessment($assessment, false);
        $completed = $this->orderEndService->getCompletedOrderId(user()->{user()->getKeyName()}, $order_id);

        if (!$feedback) {
            return response(['message' => 'Try again, near server error', 'status' => 'Failed'], 500);
        }

        return new GetAssessmentResource(compact('feedback', 'completed'));
    }

    /**
     * @param  OrderAddFeedbackRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function orderFeedback(OrderAddFeedbackRequest $request): Response|Application|ResponseFactory
    {
        $this->orderEndService->driverCompletedFeedback(
            get_user_id(),
            (int)$request->completed_order_id,
            (int)$request->assessment,
            (int)$request->option,
            $request->text,
            $request->slip
        );

        return response(['message' => 'Feedback Added', 'status' => 'ok']);
    }

    /**
     * @param  string|null  $take
     * @param  string|null  $skip
     * @return Application|ResponseFactory|AnonymousResourceCollection|Response
     */
    public function getOrders(string $take = null, string $skip = null): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        $orders = $this->orderCrud->getDriverOrdersList(user()->{user()->getKeyName()}, $take, $skip);

        if (!$orders) {
            return response(['message' => 'Error'], 500);
        }

        return OrderListResource::collection($orders);
    }

    /**
     * @param  int  $completed_order_id
     * @return OrderListTrajectoryResource
     */
    public function getOrderTrajectory(int $completed_order_id): OrderListTrajectoryResource
    {
        $resource = $this->orderCrud->getDriverOrderTrajectory($completed_order_id);

        return new OrderListTrajectoryResource($resource);
    }

    /**
     * @param  GetDayOrdersRequest  $request
     * @return Application|ResponseFactory|Response|GetDayOrdersResource
     */
    public function getDaysOrderInfo(GetDayOrdersRequest $request): Response|GetDayOrdersResource|Application|ResponseFactory
    {
        $orders = $this->driverService->getDaysOrders(user()->driver_id);

        if (!$orders) {
            return response(['message' => 'Not Orders']);
        }

        return new GetDayOrdersResource($orders);
    }

    /**
     * @param  CommonOrderRequest  $request
     * @return Application|ResponseFactory|Response|PassCommonOrderResource
     * @Get("common_order/{$order_id}", where={"order_id": "[0-9]+"}, no_prefix="true", name="driver.select.common.order")
     */
    public function selectCommonOrder(CommonOrderRequest $request): Application|ResponseFactory|Response|PassCommonOrderResource
    {
        $order = $this->driverService->acceptCommonOrder(get_user_id(), $request->order_id, $request->hash, $request->accept);

        if (!$order) {
            return response(['message' => 'failed'], 500);
        }

        return new PassCommonOrderResource($order);
    }

    /**
     * @param  OrderLateRequest  $request
     * @return Application|ResponseFactory|Response
     * @throws Lexcept
     */
    public function lateOrder(OrderLateRequest $request): Response|Application|ResponseFactory
    {
        $result = $this->workerService->lateOrder(get_user_id(), $request->minute);

        if (!$result) {
            return response(['message' => 'You are not in order'], 500);
        }

        return response(['message' => 'OK']);
    }

    /**
     * @param  GetOrderRejectOptionsRequest  $request
     * @return AnonymousResourceCollection
     * @throws UnauthorizedException
     */
    public function rejectOptions(GetOrderRejectOptionsRequest $request): AnonymousResourceCollection
    {
        $user = user();

        if (!$user) {
            throw new UnauthorizedException();
        }

        $options = $this->feedbackService->getFeedbacks($user->getMap(), false);
        $rating = $this->ratingService->getRating($request->order_id, get_user_id());

        return OrderRejectOptionsResource::collection($options)
            ->additional(['rejected_rating' => $rating->remove_rating ? $rating->remove_rating + ConstRatingPattern::RESET_AFTER_ACCEPT()->getValue() : '']);
    }

    /**
     * @param  OrderRejectRequest  $request
     * @return OrderRejectResource
     */
    public function orderReject(OrderRejectRequest $request): OrderRejectResource
    {
        $rejected = $this->driverService->driverRejectOrder($request->order_id, get_user_id(), $request->option, $request->text);

        return new OrderRejectResource($rejected);
    }

    /**
     * @param  PrepareCommonOrderRequest  $request
     * @return Application|ResponseFactory|Response|PassOrderResource
     */
    public function prepareCommonOrder(PrepareCommonOrderRequest $request): Response|PassOrderResource|Application|ResponseFactory
    {
        $prepared_order = $this->orderService->prepareCommonOrder($request->order_id, get_user_id());

        if (!$prepared_order) {
            return response(['message' => 'Error server with prepared order'], 500);
        }

        return new PassOrderResource($prepared_order);
    }
}
