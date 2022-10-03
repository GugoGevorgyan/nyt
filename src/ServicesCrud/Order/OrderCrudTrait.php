<?php

declare(strict_types=1);


namespace Src\ServicesCrud\Order;


use JetBrains\PhpStorm\ArrayShape;
use JsonException;
use Src\Models\Order\Order;
use Src\Models\SystemUsers\SystemWorker;
use Src\Models\TransportStations\Airport;
use Src\Models\TransportStations\Metro;
use Src\Models\TransportStations\RailwayStation;

/**
 * Trait OrderCrudTrait
 * @package Src\ServicesCrud\Order
 */
trait OrderCrudTrait
{

    /*-------------------------- Call center ---------------------*/

    /**
     * @param  array  $data
     * @return array
     * @throws JsonException
     */
    protected function callCenterOrderFilterData(array $data): array
    {
        $orderData['franchisee'] = ['ids' => [FRANCHISE_ID]];
        $orderData['operator_id'] = $data['operator_id'] ?? null;
        $orderData['client_id'] = $data['client_id'];
        $orderData['customer_id'] = user()->system_worker_id;
        $orderData['customer_type'] = (new SystemWorker())->getMap();
        $orderData['status_id'] = Order::ORDER_STATUS_PENDING;
        $orderData['order_type_id'] = $this->getOrderTypeId($data);
        $orderData['car_option'] = ['ids' => $data['car_option']];
        $orderData['car_class_id'] = $data['car_class_id'];
        $orderData['payment_type_id'] = $data['payment_type_id'];
        $orderData['address_from'] = $data['address_from'];
        $orderData['from_coordinates'] = isset($data['from_coordinates']) && $data['from_coordinates'] ? decode($data['from_coordinates']) : decode();
        $orderData['address_to'] = $data['address_to'];
        $orderData['to_coordinates'] = isset($data['to_coordinates']) && $data['to_coordinates'] ? decode($data['to_coordinates']) : decode();
        $orderData['comments'] = $data['comments'];

        return $orderData;
    }

    /**
     * @param $data
     * @return array
     */
    protected function callCenterMeetFilterData($data): array
    {
        $meetData['info'] = $data['info'];
        $meetData['text'] = $data['text'];

        if ($data['airport_id']) {
            $meetData['place_id'] = $data['airport_id'];
            $meetData['place_type'] = (new Airport())->getMap();
        } elseif ($data['railway_station_id']) {
            $meetData['place_id'] = $data['railway_station_id'];
            $meetData['place_type'] = (new RailwayStation())->getMap();
        } elseif ($data['metro_id']) {
            $meetData['place_id'] = $data['metro_id'];
            $meetData['place_type'] = (new Metro())->getMap();
        }

        return $meetData;
    }

    /**
     * @param $data
     * @return array
     */
    #[ArrayShape([
        'time' => 'mixed',
        'create_time' => 'mixed',
        'time_zone' => 'mixed'
    ])]
    protected function callCenterPreOrderFilterData($data): array
    {
        return [
            'time' => $data['start_time'],
            'create_time' => $data['create_time'],
            'time_zone' => $data['time_zone'],
        ];
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function callCenterPassengerFilterData($data): mixed
    {
        $data['phone'] = preg_replace('/\D/', '', $data['phone']);
        $client = $this->clientContract->where('phone', '=', $data['phone'])->findFirst();
        $client ? $this->clientContract->update($client->{$client->getKeyName()}, $data) : $client = $this->clientContract->create($data);

        return $client->client_id;
    }
}
