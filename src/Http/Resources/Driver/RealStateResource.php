<?php

declare(strict_types=1);

namespace Src\Http\Resources\Driver;

use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use JsonException;
use Src\Http\Resources\App\ApiKeysResource;
use Src\Http\Resources\BaseResource;
use Src\Models\Driver\DriverStatus;

/**
 * Class RealStateResource
 * @package Src\Http\Resources\Driver
 */
class RealStateResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     * @throws JsonException
     */
    #[ArrayShape([
        'state' => 'int|mixed',
        'is_ready' => 'mixed',
        'locked' => 'false|mixed',
        'locked_left_time' => 'int|mixed',
        'cords' => 'array',
        'keys' => '\Src\Http\Resources\App\ApiKeysResource',
        'order_id' => 'mixed|string',
        'payload' => 'array|object'
    ])]
    public function toArray($request): array
    {
        [$end_hash, $pause_hash, $payload, $routes] = $this->treatmentData();

        return [
            'state' => $this['state'],
            'is_ready' => $this['is_ready'],
            'locked' => $this['locked'] ?? false,
            'locked_left_time' => $this['locked_left'] ?? 0,
            'cords' => [$this['lat'] ?? 0.0, $this['lut'] ?? 0.0],
            'keys' => new ApiKeysResource($this['keys']),
            'order_id' => $this['state'] === DriverStatus::DRIVER_SLIP_NUMBER ? $this['order_id'] : '',

            'payload' => $payload ? [
                'order_id' => $this['order_id'] ?? '',

                'hash' => $this['on_way_hash'] ?? $this['in_place_hash'] ?? $this['in_order_hash'] ?? '',
                'hash_end' => $end_hash,
                'hash_pause' => $pause_hash,
                'routes' => $routes,

                'order' => [
                    'initial' => $this['order']['initial'] ?? '',
                    'distance' => $this['order']['distance'] ?? 0,
                    'duration' => $this['order']['duration'] ?? 0,
                    'price' => $this['order']['price'] ?? 0,
                    'client_phone' => $this['order']['client_phone'] ?? '',
                    'from_coordinates' => $this['order']['from_coordinates'] ?? (object)null,
                    'address_from' => $this['order']['address_from'] ?? '',
                    'address_to' => $this['order']['address_to'] ?? '',
                    'to_coordinates' => $this['order']['to_coordinates'] ?? (object)null,
                    'paused' => $this['order']['paused'] ?? false,
                    'pause_time' => $this['order']['pause_time'] ?? 0,
                ],
            ] : (object)null
        ];
    }

    /**
     * @return array
     */
    protected function treatmentData(): array
    {
        $end_hash = $this['end_hash'] ?? '';
        $pause_hash = $this['pause_hash'] ?? '';
        $payload = !(($this['state'] === DriverStatus::DRIVER_IS_FREE) && ($this['state'] === DriverStatus::DRIVER_SLIP_NUMBER));
        $routes = $this['routes'] ?? [];
        $routes = $routes ? OrderRoadResource::collection($routes) : [];

        return [$end_hash, $pause_hash, $payload, $routes];
    }
}
