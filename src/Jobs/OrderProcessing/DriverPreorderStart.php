<?php

declare(strict_types=1);

namespace Src\Jobs\OrderProcessing;

use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use JsonException;
use Redis;
use Src\Broadcasting\Broadcast\Driver\AcceptPreOrder;
use Src\Core\Enums\ConstQueueUuid;
use Src\Core\Enums\ConstRedis;
use Src\Models\Driver\DriverStatus;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\OrderStatus;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\Notiication\NotificationContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Order\OrderServiceContract;

use function strlen;

/**
 * @method static dispatch(int $driver_id, int $order_id, Carbon $started): selfless
 */
class DriverPreorderStart implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    /**
     * @var OrderContract
     */
    protected OrderContract $orderContract;
    /**
     * @var DriverContract
     */
    protected DriverContract $driverContract;
    /**
     * @var NotificationContract
     */
    protected NotificationContract $notificationContract;
    /**
     * @var OrderShippedDriverContract
     */
    protected OrderShippedDriverContract $shippedContract;
    /**
     * @var GeocodeServiceContract
     */
    protected GeocodeServiceContract $geoService;
    /**
     * @var OrderServiceContract
     */
    protected OrderServiceContract $orderService;
    /**
     * @var PreorderContract
     */
    protected PreorderContract $preorderContract;
    /**
     * @var Connection|Redis
     */
    protected Connection|Redis $rConnection;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected int $driver_id, protected int $order_id, protected Carbon $started)
    {
    }

    /**
     * Execute the job.
     *
     * @param  OrderContract  $orderContract
     * @param  DriverContract  $driverContract
     * @param  NotificationContract  $notificationContract
     * @param  OrderShippedDriverContract  $shippedContract
     * @param  GeocodeServiceContract  $geocodeServiceContract
     * @param  OrderServiceContract  $orderService
     * @param  PreorderContract  $preorderContract
     * @return void
     * @throws Exception
     */
    public function handle(
        OrderContract $orderContract,
        DriverContract $driverContract,
        NotificationContract $notificationContract,
        OrderShippedDriverContract $shippedContract,
        GeocodeServiceContract $geocodeServiceContract,
        OrderServiceContract $orderService,
        PreorderContract $preorderContract
    ): void {
        set_queue_id(ConstQueueUuid::preorder_start()->getValue().":$this->order_id", $this->job->getJobId());
        $this->orderContract = $orderContract;
        $this->driverContract = $driverContract;
        $this->notificationContract = $notificationContract;
        $this->shippedContract = $shippedContract;
        $this->geoService = $geocodeServiceContract;
        $this->orderService = $orderService;
        $this->preorderContract = $preorderContract;
        $this->rConnection = redis();

        $order = $this->orderContract->find($this->order_id, ['order_id', 'status_id']);

        if ($order && $order->status_id === OrderStatus::ORDER_CANCELED) {
            return;
        }

        // Detect started preorder or unpin to automatic repeat
        if (!$this->canStart()) {
            return;
        }

        // started preorder
        $this->started();

        rem_queue_id(ConstQueueUuid::preorder_start()->getValue().":$this->order_id");
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function canStart(): bool
    {
        $driver = $this->driverContract->find($this->driver_id, ['driver_id', 'current_status_id']);

        if ($driver && $driver->current_status_id === DriverStatus::DRIVER_IS_FREE) {
            return true;
        }

        $this->warning();

        return false;
    }

    /**
     * @throws Exception
     */
    protected function warning(): void
    {
        $order = $this->orderContract
            ->with([
                'preorder' => fn($query) => $query->select(['order_id', 'time']),
                'process' => fn($query) => $query->select(['order_process_id', 'order_shipped_id', 'distance_traveled', 'travel_time']),
            ])
            ->find($this->order_id, ['order_id', 'address_from', 'from_coordinates']);

        $driver = $this->driverContract
            ->with([
                'current_order' => fn($query) => $query->select(['orders.order_id', 'address_from', 'address_to', 'lat', 'lut']),
                'order_in_process_road' => fn($query) => $query->select(['order_in_process_road_id', 'shipment_driver_id', 'distance', 'duration']),
                'order_on_way_road' => fn($query) => $query->select(['order_on_way_road_id', 'shipment_driver_id', 'distance', 'duration']),
            ])
            ->find($this->driver_id, ['driver_id', 'car_id', 'current_franchise_id', 'phone']);

        if (!$driver || !$order) {
            return;
        }

        if (!$driver->current_order) {
            $this->started();
            return;
        }

        $this->warningOperation($driver, $order);
    }

    /**
     * @throws Exception
     */
    protected function started(): void
    {
        $order = $this->orderContract
            ->with([
                'preorder' => fn($query) => $query->select(['order_id', 'time']),
                'on_way_roads' => fn($query) => $query->select(['shipment_driver_id', 'order_on_way_road_id']),
                'shipped' => fn($query) => $query
                    ->where('driver_id', '=', $this->driver_id)
                    ->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
                    ->select(['driver_id', 'order_id', 'order_shipped_driver_id', 'on_way_hash'])
            ])
            ->find($this->order_id, ['order_id', 'from_coordinates', 'address_from', 'to_coordinates', 'to_coordinates', 'comments']);

        $driver = $this->driverContract->find($this->driver_id, ['driver_id', 'car_id', 'current_franchise_id', 'phone', 'current_status_id']);

        if (!$order || !$driver) {
            return;
        }

        $from_cords = $this->driverContract->getCordArray($this->driver_id);
        $road = $this->geoService->roadCalculation($from_cords, $order->from_coordinates, null, false);
        $routes = $this->orderService->createOrderProcessRoutes($order->shipped->order_shipped_driver_id, $road, true);

        $payload = [
            'order_id' => $order->order_id,
            'on_way_hash' => $order->shipped->on_way_hash,
            'address_from' => address_short($order->address_from),
            'cord_from' => $order->from_coordinates,
            'delivery_time' => now()->addMinutes(30)->format('d/m/y H:i'),
            'routes' => $routes
        ];

        [$start, $duration, $payload] = $this->broadcast($driver, $payload);
        $this->checkResponsePreorder($start, $duration, $payload);
    }

    /**
     * @param  object  $driver
     * @param  array  $payload
     * @param  string  $message
     * @param  string  $status
     * @return array
     * @throws JsonException
     */
    #[ArrayShape([
        'start' => 'object',
        'duration' => 'int',
        'payload' => 'array',
    ])]
    protected function broadcast(object $driver, array $payload, string $message = '', string $status = AcceptPreOrder::ACCEPT): array
    {
        $last_number = $this->notificationContract->firstLatest('notification_id', ['group_number'])->group_number ?? '0000000000';
        ++$last_number;

        if (Str::startsWith($last_number, '0')) {
            preg_match('/[0]+/', $last_number, $matches);
            $group_number = $matches[0].($last_number);
            $group_number = strlen($group_number) > 10 ? substr($group_number, strlen($group_number) - 10) : $group_number;
        } else {
            $group_number = $last_number;
        }

        $notification_data = [
            'group_number' => $group_number,
            'annunciator_id' => $driver->driver_id,
            'annunciator_type' => $driver->getMap(),
            'title' => trans('words.preorder.preorder', ['time' => $payload['delivery_time']]),
            'body' => $payload['address_from'],
            'payload' => json_encode($payload, JSON_THROW_ON_ERROR),
        ];

        $start = now();
        $duration = (int)config('nyt.pr_timeout');
        $payload['timeout'] = $duration;

        try {
            AcceptPreOrder::broadcast($driver, $payload, $message, $status);
//            Notification::sendNow($driver, new DriverNotify($group_number, $notification_data));
            $this->notificationContract->insert($notification_data);
        } catch (Exception $exception) {
            write($exception->getMessage());
        } finally {
            return [$start, $duration, $payload];
        }
    }

    /**
     * @param  Carbon  $start
     * @param  int  $duration
     * @param  array  $payload
     * @throws Exception
     */
    protected function checkResponsePreorder(Carbon $start, int $duration, array $payload): void
    {
        while ($start->diffInSeconds(now()) <= $duration) {
            $event = $this->shippedContract
                ->where('on_way_hash', '=', $payload['on_way_hash'])
                ->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
                ->where('current', '=', true)
                ->exists();

            if ($event) {
                break;
            }

            sleep(1);
        }

        $shipped = $this->shippedContract
            ->where('on_way_hash', '=', $payload['on_way_hash'])
            ->findFirst(['status_id', 'order_shipped_driver_id', 'current']);

        if (!$shipped) {
            return;
        }

        $continue = $shipped->status_id === OrderShippedStatus::PRE_MANUAL
            || $shipped->status_id === OrderShippedStatus::PRE_CANCELED
            || $shipped->status_id === OrderShippedStatus::PRE_UNPIN
            || $shipped->status_id === OrderShippedStatus::PRE_REJECTED;

        if ($continue) {
            return;
        }

        if (!$shipped->current && ($shipped->status_id === OrderShippedStatus::PRE_PENDING || $shipped->status_id === OrderShippedStatus::PRE_ACCEPTED)) {
            $this->driverContract->beginTransaction(function () use ($shipped) {
                if (!$this->driverContract->update($this->driver_id, ['current_status_id' => DriverStatus::DRIVER_IS_FREE])) {
                    return false;
                }

                if (!$this->shippedContract->update($shipped->order_shipped_driver_id, ['status_id' => OrderShippedStatus::PRE_REJECTED, 'current' => false])) {
                    return false;
                }
            });

            $this->orderService->autoDispatch($this->order_id);
        }
    }

    /**
     * @param  object  $driver
     * @param  object  $order
     * @throws JsonException
     * @throws Exception
     */
    protected function warningOperation(object $driver, object $order): void
    {
        $delivery_time = now()->diffInMinutes($order->preorder->time);
        $payload = [
            'order_id' => $order->order_id,
            'address_from' => address_short($order->address_from),
            'cord_from' => $order->from_coordinates,
            'delivery_time' => now()->addMinutes($delivery_time)->format('d/m/y H:i'),
            'on_way_hash' => '',
            'routes' => []
        ];

        if ($driver->current_order->address_to || ($driver->current_order->lat && $driver->current_order->lut)) {
            $this->treatmentByToPoint($order, $driver, $payload);
        } elseif (!$driver->current_order->address_to && !$driver->current_order->lat) {
            $this->treatmentByFromPoint($driver, $payload);
        }
    }

    /**
     * @param  object  $order
     * @param  object  $driver
     * @param  array  $payload
     * @throws JsonException
     */
    protected function treatmentByToPoint(object $order, object $driver, array $payload): void
    {
        $process_travel_time = $order->process->travel_time / 60;

        if (!$driver->order_in_process_road) {
            $this->treatmentByFromPoint($driver, $payload);
            return;
        }

        if ($driver->order_in_process_road->duration > $process_travel_time && 20 < ($driver->order_in_process_road->duration - $process_travel_time)) {
            $this->preorderContract->where('order_id', '=', $this->order_id)->updateSet(['accept' => false]);
            $this->orderService->autoDispatch($this->order_id);
            return;
        }

        if ($driver->order_in_process_road->duration > $process_travel_time && 20 > ($time = $driver->order_in_process_road->duration - $process_travel_time)) {
            $status = AcceptPreOrder::ANSWER;
            $message = trans('messages.preorder.answer', ['time' => now()->addMinutes($time)]);

            $this->broadcast($driver, $payload, $message, $status);

            if ($this->checkWhenAnswerQuestion($time + 1)) {
                self::class->dispatch($this->driver_id, $this->order_id, $this->started)->delay(now()->addMinutes($time + 1));
            } else {
                $this->preorderContract->where('order_id', '=', $this->order_id)->updateSet(['accept' => false]);
                $this->orderService->autoDispatch($this->order_id);
            }
        }
    }

    /**
     * @param $driver
     * @param $payload
     * @throws JsonException
     */
    protected function treatmentByFromPoint($driver, $payload): void
    {
        if ($driver->order_in_process_road) {
            $status = AcceptPreOrder::ANSWER;
            $message = trans('messages.preorder.noty_answer', ['order_time' => now()]);
            $this->broadcast($driver, $payload, $message, $status);

            if (!$this->checkWhenAnswerQuestion(1)) {
                $this->orderService->autoDispatch($this->order_id);
            }
        } else {
            $status = AcceptPreOrder::NOTY;
            $message = trans('messages.preorder.noty');
            $this->broadcast($driver, $payload, $message, $status);
            $this->orderService->autoDispatch($this->order_id);
        }
    }

    /**
     * @param  int  $add_minutes
     * @return bool
     */
    protected function checkWhenAnswerQuestion(int $add_minutes): bool
    {
        $this->rConnection
            ->hMSet(
                ConstRedis::driver_preorder_question($this->driver_id),
                ['order_id' => $this->order_id, 'started' => now()->addMinutes($add_minutes)]
            );
        $this->rConnection->expire(ConstRedis::driver_preorder_question($this->driver_id), 15);

        $just_now = now();
        $result = true;

        while ($just_now->diffInSeconds(now()) <= 15) {
            $real_shipped = $this->shippedContract
                ->where('order_id', '=', $this->order_id)
                ->where('driver_id', '=', $this->driver_id)
                ->findFirst(['status_id', 'current']);

            if ($real_shipped && ($real_shipped->status_id === OrderShippedStatus::PRE_REJECTED || !$real_shipped->current)) {
                $this->rConnection->hDel(ConstRedis::driver_preorder_question($this->driver_id), ...['order_id', 'started']);
                $result = false;
                break;
            }

            sleep(1);
        }

        return $result;
    }
}
