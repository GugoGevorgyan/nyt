<?php

declare(strict_types=1);


namespace Src\Services\Client;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\NoReturn;
use Src\Core\Enums\ConstQueue;
use Src\Core\Enums\ConstRentTimes;
use Src\Jobs\FindView\GetTaxiFromRadius;
use Src\Models\Client\BeforeAuthClient;
use Src\Models\Client\Client;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\OrderStatus;
use Src\Models\Order\PaymentType;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Order\OrderContract;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

/**
 * Trait ClientTrait
 * @package Src\Services\ClientMessage
 * @property ClientContract $clientContract
 * @property OrderContract $orderContract
 */
trait ClientTrait
{
    /**
     * @param $latitude
     * @param $longitude
     * @param $user
     */
    protected function getTaxisByFromRadius($latitude, $longitude, $user): void
    {
        GetTaxiFromRadius::dispatch($latitude, $longitude, $user)->onQueue(ConstQueue::BASE()->getValue());
    }

    /**
     * @param  BeforeAuthClient|Client  $client
     * @param  array|null  $cords
     * @return array
     */
    #[NoReturn] protected function getClientInitData(Client|BeforeAuthClient $client, array $cords = null): array
    {
        $client_has_corporate = $this->clientContract->has('corporate')->find($client->{$client->getKeyName()}, ['client_id']);

        $payment_types = $client_has_corporate && $client->getMap() === $this->clientContract->getMap()
            ? $this->paymentTypeContract->findAll(['payment_type_id', 'type', 'name'])
            : $this->paymentTypeContract->where('type', '!=', PaymentType::COMPANY)->findAll(['payment_type_id', 'type', 'name']);

        $car_classes = $cords
            ? $this->carService->getCarClassesWithMinPrice($cords)
            : $this->carClassContract->findAll(['car_class_id', 'class_name', 'image'])->sortBy('car_class_id');

        // @TODO N+1 QUERY |>
        if ($cords && $car_classes) {
            $car_classes->map(fn($item) => $item->options = $this->carService->getOptions($item->car_class_id, $item->tariff_id));
        } elseif (!$cords) {
            $car_classes->map(fn($item) => $item->options = $this->carService->getCarOptions(['price', 'option', 'car_option_id']));
        }
        // @TODO N+1 QUERY |>

        $companies = !Auth::guard('before_clients_web')->check()
            ? $this->clientContract
                ->with(['corporateCompanies' => fn(HasManyDeep $query) => $query->select(['companies.company_id', 'companies.name', 'car_classes_ids'])])
                ->find($client->{$client->getKeyName()}, ['client_id', 'phone', 'in_order'])
            : [];

        $companies = $companies->corporateCompanies ?? $companies;
        $pay_cards = $this->payCardContract
            ->where('owner_id', '=', $client->client_id)->where('owner_type', '=', $client->getMap())
            ->findAll(['pay_card_id', 'owner_id', 'owner_type', 'card_number']);

        $rent_times = $car_classes->count() ? $this->tariffService->getRentTimesByData($car_classes[0]['car_class_id']) : array_values(ConstRentTimes::getAll());

        return compact('payment_types', 'car_classes', 'companies', 'pay_cards', 'rent_times');
    }

    /**
     * @param  Model  $client
     * @return Collection
     */
    protected function clientOnlineMasterOnline(Model $client): Collection
    {
        if ($client->in_order) {
            $client->load([
                'current_order' => fn(HasOne $order_query) => $order_query
                    ->select([
                        'orders.order_id',
                        'client_id',
                        'orders.car_class_id',
                        'address_from',
                        'address_to',
                        'from_coordinates',
                        'to_coordinates',
                        'status_id',
                    ])
            ]);

            if (!$client->current_order) {
                return $this->parseResult($instance);
            }

            if ($client->current_order->status_id === OrderStatus::ORDER_PENDING) {
                return $this->parseResult($instance, ['status', 'order'], [OrderStatus::ORDER_PENDING, $client->order]);
            }

            if ($client->current_order->status_id === OrderStatus::ORDER_IN_PROCESS) {
                $shipment = $this->orderContract
                    ->with([
                        'ordering_shipment' => fn(HasOne $shipment_query) => $shipment_query
                            ->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
                            ->with('driver:driver_id')
                            ->select(['order_shipped_driver_id', 'driver_id', 'order_id', 'status_id'])
                    ])
                    ->find(
                        $client->current_order->order_id,
                        ['client_id', 'status_id', 'from_coordinates', 'to_coordinates', 'order_id']
                    );

                $driver = $this->getDriverUpdatedData($shipment->ordering_shipment->driver->driver_id);

                return $this->parseResult(
                    $instance,
                    ['status', 'order', 'driver', 'in_order'],
                    [OrderStatus::ORDER_IN_PROCESS, $client->current_order, $driver, $client->in_order]
                );
            }
        }

        return $this->parseResult(
            $instance,
            ['client_id', 'name', 'surname', 'phone', 'in_order'],
            [$client->client_id, $client->name, $client->surname, $client->phone, $client->in_order]
        );
    }

    /**
     * @param  Model  $client
     */
    protected function clientOnlineMasterOffline(Model $client): void
    {
        $this->clientContract->update($client->{$client->getKeyName()}, ['online' => 0]);

        if ($client::has('drivers_road_view')->exists() && $client::has('coordinate')->exists() && $client::has('drivers_view')->exists()) {
            $client->drivers_view()->delete();
        }
    }

}
