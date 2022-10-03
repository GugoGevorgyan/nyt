<?php

declare(strict_types=1);

namespace Src\Listeners\AdminCorporate;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Src\Broadcasting\Broadcast\AdminCorporate\CancelOrder;
use Src\Broadcasting\Broadcast\AdminCorporate\CompletedOrder;
use Src\Broadcasting\Broadcast\AdminCorporate\CreateOrder;
use Src\Core\Enums\ConstQueue;
use Src\Events\Order\OrderCreateEvent;
use Src\Events\Order\OrderStatusUpdate;
use Src\Http\Resources\AdminCorporate\OrderHistoryResource;
use Src\Models\Order\OrderStatus;
use Src\Models\Order\PaymentType;
use Src\Repositories\AdminCorporate\AdminCorporateContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCorporate\OrderCorporateContract;
use Src\Services\Client\ClientServiceContract;

/**
 * Class CorpAdminCreateOrderListen
 * @package Src\Listeners\AdminCorporate
 */
class UpdateOrderListen
{
    /**
     * Create the event listener.
     *
     * @param  AdminCorporateContract  $corporateContract
     * @param  ClientServiceContract  $clientService
     * @param  OrderCorporateContract  $orderCorporateContract
     * @param  OrderContract  $orderContract
     */
    public function __construct(
        protected AdminCorporateContract $corporateContract,
        protected ClientServiceContract $clientService,
        protected OrderCorporateContract $orderCorporateContract,
        protected OrderContract $orderContract
    ) {
    }

    /**
     * Handle the event.
     *
     * @param  OrderStatusUpdate|OrderCreateEvent  $event
     * @return void
     */
    public function handle($event): void
    {
        if (!$event->order || PaymentType::COMPANY !== $event->order->payment_type_id) {
            return;
        }

        $company = $this->orderCorporateContract->where('order_id', '=', $event->order->order_id)->findFirst(['order_corporate_id', 'order_id', 'company_id']);
        $admins = $this->corporateContract->where('company_id', '=', $company->company_id)->findAll(['admin_corporate_id', 'franchise_id', 'company_id']);

        foreach ($admins as $admin) {
            switch ($event->order->status_id) {
                case $event->order->status_id === OrderStatus::ORDER_PENDING:
                    CreateOrder::broadcast($admin, new OrderHistoryResource($this->getOrderData($event->order->order_id, $company->company_id)));
                    break;
                case $event->order->status_id === OrderStatus::ORDER_CANCELED:
                    CancelOrder::broadcast($admin, new OrderHistoryResource($this->getOrderData($event->order->order_id, $company->company_id)));
                    break;
                case $event->order->status_id === OrderStatus::ORDER_COMPLETED:
                    CompletedOrder::broadcast($admin, new OrderHistoryResource($this->getOrderData($event->order->order_id, $company->company_id)));
                    break;
                default:
            }
        }
    }

    /**
     * @param $order_id
     * @param $company_id
     * @return object|null
     */
    protected function getOrderData($order_id, $company_id): ?object
    {
        return $this->orderContract
            ->whereHas('corporate', fn(Builder $query) => $query->where('company_id', '=', $company_id))
            ->where('payment_type_id', '=', PaymentType::getTypeId(PaymentType::COMPANY))
            ->with(
                [
                    'status' => fn($query) => $query->select(['*']),
                    'stage' => fn($query) => $query->select('*'),
                    'passenger' => fn($query) => $query->select(['client_id', 'phone', 'name', 'surname', 'patronymic']),
                    'process' => fn(HasOneThrough $query) => $query->select(['order_process_id', 'order_shipped_id', 'distance_traveled', 'travel_time']),
                    'on_way_road' => fn(HasOneThrough $query) => $query->select(['order_on_way_road_id', 'shipment_driver_id', 'route', 'real_road']),
                    'in_process_road' => fn(HasOneThrough $query) => $query->select(['order_in_process_road_id', 'shipment_driver_id', 'route', 'real_road']),
                    'completed' => fn($query) => $query->select([
                        'completed_order_id',
                        'order_id',
                        'cost',
                    ]),
                    'crossing' => fn($query) => $query->select([
                        'in_price',
                        'out_price',
                        'in_distance',
                        'out_distance',
                        'in_duration',
                        'out_duration',
                        'in_trajectory',
                        'out_trajectory'
                    ]),
                    'corporate_clients' => fn($query) => $query
                        ->where('company_id', '=', $company_id)
                        ->select([
                            'corporate_client_id',
                            'corporate_clients.client_id',
                            'corporate_clients.company_id',
                            'corporate_clients.name',
                            'corporate_clients.surname',
                            'corporate_clients.patronymic'
                        ]),
                    'corporate' => fn($query) => $query->select(['order_corporate_id', 'order_id', 'slip_number']),
                    'driver' => fn($query) => $query
                        ->with(['driver_info', 'car'])
                        ->select(['drivers.driver_id', 'drivers.driver_info_id', 'drivers.car_id', 'drivers.phone', 'current_status_id']),
                    'completed_driver' => fn($query) => $query
                        ->with(['driver_info', 'car'])
                        ->select(['drivers.driver_id', 'drivers.driver_info_id', 'drivers.car_id', 'drivers.phone']),
                ]
            )
            ->find($order_id);
    }

    /**
     * @return mixed
     */
    public function viaQueue()
    {
        return ConstQueue::OBSERVER()->getValue();
    }
}
