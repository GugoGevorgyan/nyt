<?php

declare(strict_types=1);

namespace Src\Http\Resources\AdminCorporate;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\Car\CarResource;
use Src\Http\Resources\Models\OrderStageResource;
use Src\Http\Resources\Models\RoadResource;
use Src\Http\Resources\Models\TariffResource;
use Src\Models\Order\OrderStatus;

/**
 * Class OrderHistoryResource
 * @package Src\Http\Resources\AdminCorporate
 */
class OrderHistoryResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $driver = $this['status_id'] === OrderStatus::getStatusId(OrderStatus::ORDER_COMPLETED) ? 'completed_driver' : 'driver';

        return [
            'order' => [
                'order_id' => $this['order_id'],
                'status_id' => $this['status_id'],
                'from' => $this['address_from'],
                'to' => $this['address_to'] ?? '',
                'comments' => $this['comments'] ?? '',
                'from_cord' => $this['from_coordinates'],
                'to_cord' => $this['to_coordinates'] ?? '',
                'platform' => $this['platform'] ?? '',
                'price' => $this['completed']['cost'] ?? '?',
                'initial_price' => $this['initial_data']['price'] ?? '?',
                'currency' => session('app_system.currency') ?? 'RUB',
                'slip' => $this['corporate']['slip_number'] ?? '?',
                'created' => Carbon::parse($this['created_at'])->format('Y-m-d H:i') ?? '',
            ],
            'details' => [
                'destination_address' => $this['completed']['destination_address'] ?? '',
                'destination_lat' => $this['completed']['destination_lat'] ?? '',
                'destination_lut' => $this['completed']['destination_lut'] ?? '',
                'in_price' => $this['crossing']['in_price'] ?? 0,
                'out_price' => $this['crossing']['out_price'] ?? 0,
                'in_distance' => $this['crossing']['in_distance'] ?? 0,
                'out_distance' => $this['crossing']['out_distance'] ?? 0,
                'in_duration' => $this['crossing']['in_duration'] ?? 0,
                'out_duration' => $this['crossing']['out_duration'] ?? 0,
                'in_trajectory' => $this['crossing']['in_trajectory'] ?? [],
                'out_trajectory' => $this['crossing']['out_trajectory'] ?? [],
                'distance' => $this['completed']['distance'] ?? '',
                'duration' => $this['completed']['duration'] ?? '',
                'initial_price' => $this['initial_data']['price'] ?? '',
                'minimal_price' => $this['initial_data']['initial_tariff']['minimal_price'] ?? '',
                'initial_tariff' => $this['initial_data']['initial_tariff']['name'] ?? '',
                'second_tariff' => $this['initial_data']['second_tariff']['name'] ?? '',
                'initial_price_km' => $this['initial_data']['initial_tariff']['price_km'] ?? '',
                'initial_price_minute' => $this['initial_data']['initial_tariff']['price_min'] ?? '',
            ],
            'status' => $this['status'] ?? '' ? [
                'id' => $this['status_id'] ?? '',
                'color' => $this['status']['color'] ?? '',
                'text' => $this['status']['text'] ? trans($this['status']['text']) : ''
            ] : [],
            'driver' => [
                'phone' => $this[$driver]['phone'] ?? '',
                'name' => $this[$driver]['driver_info']['name'] ?? '',
                'surname' => $this[$driver]['driver_info']['surname'] ?? '',
                'patronymic' => $this[$driver]['driver_info']['patronymic'] ?? '',
                'email' => $this[$driver]['driver_info']['email'] ?? '',
                'experience' => $this[$driver]['driver_info']['experience'] ?? 0,
                'photo' => $this[$driver]['driver_info']['photo'] ?? '',
                'status_id' => $this[$driver]['current_status_id'] ?? '',
            ],
            'client' => [
                'client_id' => $this['client_id'] ?? '',
                'name' => $this['corporate_clients']['client']['name'] ?? '',
                'surname' => $this['corporate_clients']['client']['surname'] ?? '',
                'patronymic' => $this['corporate_clients']['client']['patronymic'] ?? '',
                'email' => $this['corporate_clients']['client']['email'] ?? '',
                'phone' => $this['corporate_clients']['client']['phone'] ?? '',
                'assessment' => $this['corporate_clients']['client']['mean_assessment'] ?? '',
                'passenger' => [
                    'email' => $this['passenger']['client']['email'] ?? '',
                    'phone' => $this['passenger']['client']['phone'] ?? '',
                    'assessment' => $this['passenger']['client']['mean_assessment'] ?? '',
                ],
            ],
            'car' => $this[$driver] && $this[$driver]['car'] ? new CarResource($this[$driver]['car']) : [],
            'stage' => $this['stage'] ? new OrderStageResource($this['stage']) : [],
            'tariff' => $this['tariff'] ? new TariffResource($this['tariff']) : [],
            'road' => [
                'way' => $this['on_way_road'] ? new RoadResource($this['on_way_road']) : [],
                'process' => $this['in_process_road'] ? new RoadResource($this['in_process_road']) : [],
            ],
        ];
    }
}
