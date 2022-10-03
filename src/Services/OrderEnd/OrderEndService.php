<?php

declare(strict_types=1);


namespace Src\Services\OrderEnd;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;
use ServiceEntity\BaseService;
use Src\Broadcasting\Broadcast\Client\AdminOrderCancel;
use Src\Broadcasting\Broadcast\Client\ClientOrderEndData;
use Src\Broadcasting\Broadcast\Driver\ClientOrderCancel;
use Src\Core\Complex\CalcCrossingRoad;
use Src\Core\Complex\OrderEndCalc;
use Src\Exceptions\Lexcept;
use Src\Models\Driver\DriverStatus;
use Src\Models\Order\Order;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\OrderStatus;
use Src\Models\Order\PaymentType;
use Src\Models\SystemUsers\SystemWorker;
use Src\Repositories\CanceledOrder\CanceledOrderContract;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\ClientFavoriteDriver\ClientFavoriteDriverContract;
use Src\Repositories\CompletedOrder\CompletedOrderContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\InitialOrderData\InitialOrderDataContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCorporate\OrderCorporateContract;
use Src\Repositories\OrderCrossing\OrderCrossingContract;
use Src\Repositories\OrderFeedback\OrderFeedbackContract;
use Src\Repositories\OrderFeedbackOption\OrderFeedbackOptionContract;
use Src\Repositories\OrderProcess\OrderProcessContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\OrderStatus\OrderStatusContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Repositories\Tariff\TariffContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\Payment\PaymentServiceContract;
use Src\Services\RatingPointService\RatingPointServiceContract;

/**
 * Class OrderEndService
 * @package Src\Services\OrderEnd
 */
final class OrderEndService extends BaseService implements OrderEndServiceContract
{
    use OrderEndTrait;

    /**
     * @param  OrderContract  $orderContract
     * @param  OrderStatusContract  $orderStatusContract
     * @param  OrderShippedDriverContract  $shippedContract
     * @param  DriverContract  $driverContract
     * @param  ClientContract  $clientContract
     * @param  OrderFeedbackOptionContract  $feedbackOptionContract
     * @param  CanceledOrderContract  $canceledOrderContract
     * @param  RatingPointServiceContract  $ratingService
     * @param  OrderServiceContract  $orderService
     * @param  ClientServiceContract  $clientService
     * @param  CompletedOrderContract  $completedContract
     * @param  GeocodeServiceContract  $geoService
     * @param  OrderFeedbackContract  $orderFeedbackContract
     * @param  OrderProcessContract  $processContract
     * @param  OrderCorporateContract  $orderCorporateContract
     * @param  TariffContract  $tariffContract
     * @param  OrderCrossingContract  $orderCrossingContract
     * @param  InitialOrderDataContract  $orderInitialContract
     * @param  PaymentServiceContract  $paymentService
     */
    public function __construct(
        protected OrderContract $orderContract,
        protected OrderStatusContract $orderStatusContract,
        protected OrderShippedDriverContract $shippedContract,
        protected DriverContract $driverContract,
        protected ClientContract $clientContract,
        protected OrderFeedbackOptionContract $feedbackOptionContract,
        protected CanceledOrderContract $canceledOrderContract,
        protected RatingPointServiceContract $ratingService,
        protected OrderServiceContract $orderService,
        protected ClientServiceContract $clientService,
        protected CompletedOrderContract $completedContract,
        protected GeocodeServiceContract $geoService,
        protected OrderFeedbackContract $orderFeedbackContract,
        protected OrderProcessContract $processContract,
        protected OrderCorporateContract $orderCorporateContract,
        protected TariffContract $tariffContract,
        protected OrderCrossingContract $orderCrossingContract,
        protected InitialOrderDataContract $orderInitialContract,
        protected PaymentServiceContract $paymentService,
        protected ClientFavoriteDriverContract $favoriteDriverContract,
        protected PreorderContract $preorderContract
    ) {
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function driverShippedOrderReject($driver_id, $order_id, $hash): ?object
    {
        //@todo optimize
        $order_rating = $this->shippedContract
            ->where('accept_hash', '=', $hash)
            ->with(['estimated_rating' => fn($query) => $query->select(['driver_id', 'order_id', 'estimated_rating_id', 'remove_patterns'])])
            ->findFirst([$this->shippedContract->getKeyName(), 'driver_id', 'order_id', 'accept_hash', 'estimated_rating_id']);

        if (!$this->shippedContract->getCurrentPendingShipped($order_id, $driver_id)) {
            return $order_rating->estimated_rating;
        }

        $this->shippedContract
            ->where('driver_id', '=', $driver_id)
            ->where('order_id', '=', $order_id)
            ->updateSet([
                'status_id' => OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_REJECTED),
                'current' => 0
            ]);

        $this->ratingService->setDriverRating($driver_id, $order_id, $order_rating->estimated_rating->remove_patterns['ids']);

        return $order_rating->estimated_rating;
    }

    /**
     * @inheritDoc
     */
    public function getFeedbackOptions(): Collection
    {
        return $this->feedbackOptionContract->findAll(['type', 'value']);
    }

    /**
     * @param $order_id
     * @return bool
     * @throws Exception
     */
    public function callCenterCancelOrder($order_id): bool
    {
        $order = $this->orderContract
            ->with([
                'current_shipped.driver.car',
                'client' => fn($query) => $query->select(['client_id', 'phone']),
                'preorder' => fn($query) => $query->select(['preorder_id', 'order_id']),
            ])
            ->find($order_id);

        if (!$order) {
            return false;
        }

        $this->orderContract->beginTransaction(function () use ($order, $order_id) {
            $this->orderContract->forgetCache();

            $data = [
                'order_id' => $order_id,
                'driver_id' => $order->current_shipped->driver_id ?? null,
                'car_id' => $order->current_shipped?->driver->car_id,
                'cancelable_id' => get_user_id(),
                'cancelable_type' => (new SystemWorker())->getMap()
            ];

            $this->canceledOrderContract->create($data);
            $this->clientContract->update($order->client->client_id, ['in_order' => false]);
            $this->orderContract->update($order_id, ['status_id' => Order::ORDER_STATUS_CANCELED]);
            $order->preorder ? $this->preorderContract->update($order->preorder->preorder_id, ['active' => false]) : null;
            $driver = $this->orderService->getOrderDriver($order_id);

            if ($driver) {
                $this->driverContract->update($driver->driver_id, ['current_status_id' => DriverStatus::getStatusId(DriverStatus::DRIVER_IS_FREE)]);
                $this->shippedContract
                    ->where('driver_id', '=', $driver->driver_id)
                    ->where('order_id', '=', $order_id)
                    ->updateSet(['status_id' => OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_CANCELED), 'current' => false]);

                ClientOrderCancel::broadcast($driver, $order);
            }

            $client = $this->clientContract->find($order->client->client_id, ['client_id', 'phone']);
            AdminOrderCancel::broadcast($client, trans('messages.order_canceled'));

            $this->removeRedisKeys($order_id, $order->client_id);
        });

        return true;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function clientCancelOrder($client_id): bool|array
    {
        [$client, $driver, $order] = $this->getClientCancelPayload($client_id);

        if (!$client || !$client->current_order) {
            return false;
        }

        if ($driver && $driver->current_status_id === DriverStatus::getStatusId(DriverStatus::DRIVER_IN_PLACE)) {
            $cancel_price = $this->detectCancelFee($order->order_id, $order->from_coordinates);

            $waiting = $this->orderInitialContract
                ->where('order_id', '=', $order->order_id)
                ->findFirst(['waiting_cancel', 'order_id', 'order_initial_data_id']);

            if ($waiting && $cancel_price && $waiting->waiting_cancel) {
                [$create_aborted_order, $aborted_options] = $this->cancelCreated($order, $client, $driver);
            }

            if ($cancel_price && !$waiting->waiting_cancel) {
                $this->orderInitialContract->where('order_id', '=', $order->order_id)->updateSet(['waiting_cancel' => 1]);
            }
        } else {
            [$create_aborted_order, $aborted_options] = $this->cancelCreated($order, $client, $driver);
        }

        return [
            'id' => $create_aborted_order ?? 0,
            'options' => $aborted_options ?? [],
            'cancel_price' => $cancel_price ?? 0
        ];
    }

    /**
     * @throws Exception
     */
    #[ArrayShape([
        'id' => 'int|mixed',
        'options' => 'array|mixed',
        'cancel_price' => 'int'
    ])]
    public function clientAcceptCancel(int $client_id, bool $cancel = false): array
    {
        [$client, $driver, $order] = $this->getClientCancelPayload($client_id);

        $waiting = $this->orderInitialContract
            ->where('order_id', '=', $order->order_id)
            ->findFirst(['waiting_cancel', 'order_id', 'order_initial_data_id']);

        if ($cancel && $waiting->waiting_cancel) {
            $process = $this->processContract
                ->whereHas('order', fn($query) => $query->where('orders.order_id', '=', $order->order_id))
                ->findFirst(['order_process_id', 'order_shipped_id', 'cancel_price']);

            $data = $this->paymentService->driverPercent($order->order_id, $driver->driver_id, $process->cancel_price, $order->payment_type_id);

            [$create_aborted_order, $aborted_options] = $this->cancelCreated($order, $client, $driver, $data);
        } else {
            $this->orderInitialContract->where('order_id', '=', $client->current_order->order_id)->updateSet(['waiting_cancel' => 0]);
        }

        return [
            'id' => $create_aborted_order ?? 0,
            'options' => $aborted_options ?? [],
            'cancel_price' => 0
        ];
    }

    /**
     * @param $order_id
     * @param $client_id
     * @return bool
     * @throws Exception
     */
    public function companyOrderEnd($order_id, $client_id): bool
    {
        if (!$this->orderEndStatuses($order_id, OrderStatus::ORDER_CANCELED)) {
            return false;
        }

        $driver = $this->orderService->getOrderDriver($order_id);
        $this->orderContract->create([
            'order_id' => $order_id,
            'client_id' => $client_id,
            'cancelable_id' => user()->{user()->getKeyName()},
            'cancelable_type' => user()->getMap()
        ]);

        if ($driver) {
            $order = $this->orderContract->find($order_id);
            ClientOrderCancel::broadcast($driver, $order);
        }

        $client = $this->clientContract->find($client_id, ['client_id', 'phone']);
        AdminOrderCancel::broadcast($client, 'Order canceled');

        $this->removeRedisKeys($order_id, $client_id);

        return true;
    }

    /**
     * @param $order_id
     * @param  int  $order_status
     * @return bool
     * @throws Exception
     * @noinspection MultipleReturnStatementsInspection
     */
    public function orderEndStatuses($order_id, $order_status = OrderStatus::ORDER_COMPLETED): bool
    {
        $this->orderContract->beginTransaction();

        if (!$this->shippedContract
            ->where('order_id', '=', $order_id)
            ->where('status_id', '=', OrderShippedStatus::getStatusId(OrderShippedStatus::PRE_ACCEPTED))
            ->updateSet(['current' => false])
        ) {
            $this->orderContract->rollBack();
            return false;
        }

        if (!$this->clientContract->whereHas('order', fn($query) => $query->where('order_id', '=', $order_id))->updateSet(['in_order' => false])) {
            $this->orderContract->rollBack();
            return false;
        }

        if (!$this->driverContract
            ->whereHas('order_shipment', fn($query) => $query->where('order_id', '=', $order_id))
            ->updateSet(['current_status_id' => DriverStatus::getStatusId(DriverStatus::DRIVER_IS_FREE)])
        ) {
            $this->orderContract->rollBack();
            return false;
        }

        if (!$this->orderContract->where('order_id', '=', $order_id)->updateSet(['status_id' => OrderStatus::getStatusId($order_status)])) {
            $this->orderContract->rollBack();
            return false;
        }

        if (!$this->preorderContract->where('order_id', '=', $order_id)->updateSet(['active' => false])) {
            $this->orderContract->rollBack();
            return false;
        }

        $this->orderContract->commit();
        return true;
    }

    /**
     * @inheritDoc
     * @throws Exception
     * @noinspection MultipleReturnStatementsInspection
     */
    public function createClientAbortedFeedback($client_id, $aborted_order_id, array $feedback = []): bool
    {
        $this->orderContract->beginTransaction();
        $this->orderContract->forgetCache();

        $order = $this->clientContract
            ->whereHas('canceled_orders',
                fn(Builder $aborted_order) => $aborted_order
                    ->where('canceled_order_id', '=', $aborted_order_id)
                    ->doesntHave('feedbacks'),
            )
            ->with('current_canceled_order')
            ->findFirst();

        if (!$order || !$order->current_canceled_order->count()) :
            $this->orderContract->rollBack();
            return false;
        endif;

        $create_feedback = $order->current_canceled_order->feedbacks()
            ->create([
                'order_id' => $order->current_canceled_order->order_id,
                'feedback_option_id' => $feedback['option_id'],
                'writable_id' => $order->{$order->getKeyName()},
                'writable_type' => $order->getMap(),
                'text' => $feedback['text'] ?? null,
            ]);

        if (!$create_feedback) :
            $this->orderContract->rollBack();
            return false;
        endif;

        $this->orderContract->commit();

        return true;
    }

    /**
     * @inheritDoc
     * @throws Exception
     * @noinspection MultipleReturnStatementsInspection
     */
    public function clientCompletedFeedback($client_id, $completed_order_id, array $feedback, bool $favorite = null): bool
    {
        $completed = $this->completedContract->find($completed_order_id, [$this->completedContract->getKeyName(), 'driver_id', 'order_id']);
        $option = $this->feedbackOptionContract
            ->where('option', '=', $feedback['option_id'])
            ->findFirst(['order_feedback_option_id', 'option']);

        if (!$completed) {
            return false;
        }

        $this->orderContract->beginTransaction();
        $this->orderContract->forgetCache();

        $create_feedback = $this->orderFeedbackContract->create(
            [
                'orderable_type' => $completed->getMap(),
                'orderable_id' => $completed->completed_order_id,
                'order_id' => $completed->order_id,
                'feedback_option_id' => $option->order_feedback_option_id ?? null,
                'writable_id' => $client_id,
                'writable_type' => $this->clientContract->getMap(),
                'readable_id' => $completed->driver_id,
                'readable_type' => $this->driverContract->getMap(),
                'text' => $feedback['text'],
                'assessment' => $feedback['assessment'],
            ]
        );

        if (!$create_feedback) {
            $this->orderContract->rollBack();
            return false;
        }

        $driver = $this->driverContract
            ->with(['assessed' => fn(MorphMany $query) => $query->select('readable_id', 'readable_type', 'assessment')])
            ->find($completed->driver_id, ['driver_id', 'mean_assessment']);

        if (!$driver) {
            $this->orderContract->rollBack();
            return false;
        }

        if ($favorite && !$this->favoriteDriverContract->create(['client_id' => $client_id, 'driver_id' => $driver->driver_id])) {
            $this->orderContract->rollBack();
            return false;
        }

        $this->orderContract->commit();

        if (!$this->driverContract->update($driver->driver_id, ['mean_assessment' => $driver->assessed->avg('assessment')])) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws Exception
     * @noinspection MultipleReturnStatementsInspection
     */
    public function driverCompletedFeedback(
        $driver_id,
        $completed_order_id,
        int $assessment,
        int $option = null,
        string $text = null,
        $slip_number = null
    ): bool {
        $completed = $this->completedContract->find($completed_order_id, [$this->completedContract->getKeyName(), 'driver_id', 'order_id']);

        if (!$completed) {
            throw new Lexcept('Order is not completed', 423);
        }

        $client = $this->clientService->getOrderedClientData($completed->order_id);

        $this->orderContract->beginTransaction();
        $this->orderContract->forgetCache();

        $create_feedback = $this->orderFeedbackContract->create(
            [
                'order_id' => $completed->order_id,
                'orderable_id' => $completed->completed_order_id,
                'orderable_type' => $completed->getMap(),
                'feedback_option_id' => $option,
                'writable_id' => $driver_id,
                'writable_type' => $this->driverContract->getMap(),
                'readable_id' => $client->client_id,
                'readable_type' => $this->clientContract->getMap(),
                'text' => $text,
                'assessment' => $assessment,
            ]
        );

        if (!$create_feedback) {
            $this->orderContract->rollBack();
            return false;
        }

        if ($slip_number && !$this->createOrderSlip($completed->order_id, $driver_id, (int)$slip_number)) {
            $this->orderContract->rollBack();
            return false;
        }

        $client->load(['assessed' => fn(MorphMany $query) => $query->select('assessment', 'readable_id', 'readable_type')]);

        if (!$this->clientContract->update($client->client_id, ['mean_assessment' => $client->assessed->avg('assessment')])) {
            $this->orderContract->rollBack();
            return false;
        }

        $this->orderContract->commit();
        return true;
    }

    /**
     * @inheritDoc
     */
    public function driverOrderEndStatuses($order_id): void
    {
        if (!$this->orderEndStatuses($order_id)) {
            return;
        }

        $this->removeRedisKeys($order_id);
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     * @throws Lexcept
     * @throws Exception
     */
    public function orderEndreCalculate($payload): Collection
    {
        $cords = ['start' => $payload->order_stages->start, 'end' => $payload->order_stages->end];
        $tariff_id = $payload->initial_order->initial_tariff_id;
        $region_id = $payload->initial_order->region_id;
        $real_road = $payload->in_process_road->real_road;

        [$price, $crossing_price] = $this->endCalcOrder($payload, $cords, $tariff_id, $region_id, $real_road);

        $create_completed = $this->createCompleted($payload, $crossing_price, $cords, $price, $tariff_id);

        if (!$create_completed) {
            throw new Lexcept('Error in ended order double saving order end data', 400);
        }

        if ($client = $this->clientService->getOrderedClientData($payload->order_id, ['client_id', 'phone'])) {
            $this->removeRedisKeys($payload->order_id, $client->client_id);

            ClientOrderEndData::broadcast(
                $client,
                $create_completed->{$create_completed->getKeyName()},
                $price->get('price'),
                $price->get('currency'),
                $payload->process->distance_traveled,
                $payload->process->travel_time,
            );
        } else {
            $this->removeRedisKeys($payload->order_id);
        }

        return $this->parseResult(
            $instance,
            ['distance', 'duration', 'pause', 'end_address', 'price'],
            [$create_completed->distance, $create_completed->duration, $payload->process->pause_time, $create_completed->destination_address, $price]
        );
    }

    /**
     * @param $payload
     * @param $cords
     * @param $tariff_id
     * @param $region_id
     * @param $real_road
     * @return array|null
     * @throws ReflectionException
     */
    protected function endCalcOrder($payload, $cords, $tariff_id, $region_id, $real_road): ?array
    {
        $price = OrderEndCalc::complex($payload->order, $payload->process, $payload->initial_order, $cords);
        $crossing_price = CalcCrossingRoad::complex($payload->order->order_id, $tariff_id, $region_id, $cords, $real_road, $payload->process);
        $crossing_price ? $price['price'] = $crossing_price['cost'] : null;

        return [$price, $crossing_price];
    }

    /**
     * @param $tariff_id
     * @param  object  $payload
     * @param  array|null  $cross_data
     * @return array{distance: int|float|string, duration: int|float|string}
     */
    #[ArrayShape([
        'distance' => 'float|int',
        'duration' => 'float|int'
    ])]
    public function distanceDurationPrice($tariff_id, object $payload, ?array $cross_data): array
    {
        if ($cross_data) {
//            if (is_assoc($cross_data)) {
//                $distance = 0;
//                $duration = 0;
//                foreach ($cross_data as $cross_datum) {
//                    $distance += $cross_datum['in_distance_price'] + $cross_datum['out_distance_price'];
//                    $duration += $cross_datum['in_duration_price'] + $cross_datum['out_duration_price'];
//                }
//            } else {
            $distance = round_d($cross_data['in_distance_price'] + $cross_data['out_distance_price']);
            $duration = round_t($cross_data['in_duration_price'] + $cross_data['out_duration_price']);
//            }
        } else {
            $tariff = $this->tariffContract
                ->with(['current_tariff' => fn(MorphTo $query) => $query->select('*')])
                ->find($tariff_id, ['tariff_id', 'tariff_type_id', 'car_class_id', 'tariffable_id', 'tariffable_type'])
                ->current_tariff
                ->toArray();

            if (($tariff['price_km'] ?? 0) && ($tariff['price_min'] ?? 0)) {
                $distance = round_d($payload->process->distance_traveled) * $tariff['price_km'];
                $duration = round_t($payload->process->travel_time - $payload->process->pause_time) * $tariff['price_min'];
            }
        }

        return ['distance' => $distance ?? 0, 'duration' => $duration ?? 0];
    }

    /**
     * @inheritDoc
     */
    public function getFeedbackByAssessment(int $assessment, bool $client = true, bool $completed = true): ?Collection
    {
        return $this->feedbackOptionContract
            ->when($client, fn(Builder $query) => $query->where('owner_type', '=', $this->clientContract->getMap()))
            ->when(!$client, fn(Builder $query) => $query->where('owner_type', '=', $this->driverContract->getMap()))
            ->where('completed', '=', $completed)
            ->whereRaw("FIND_IN_SET($assessment, assessment)")
            ->findAll(['name', 'order_feedback_option_id', 'option', 'assessment']);
    }

    /**
     * @param $driver_id
     * @param $order_id
     * @return mixed
     */
    public function getCompletedOrderId($driver_id, $order_id)
    {
        return $this->completedContract
            ->where('order_id', '=', $order_id)
            ->where('driver_id', '=', $driver_id)
            ->findFirst(['completed_order_id', 'order_id', 'driver_id']);
    }

    /**
     * @inheritDoc
     */
    public function slipMaster(int $order_id): Collection
    {
        $slip = 0;
        $type = $this->orderContract->find($order_id, ['order_id', 'payment_type_id']);

        if ($type->payment_type_id === PaymentType::getTypeId(PaymentType::COMPANY)) {
            $slip = 1;
        }

        return $this->parseResult($instance, ['slip'], [$slip]);
    }
}
