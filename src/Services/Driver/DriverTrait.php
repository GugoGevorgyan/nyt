<?php

declare(strict_types=1);


namespace Src\Services\Driver;


use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use JsonException;
use Src\Exceptions\Lexcept;

/**
 * Trait DriverTrait
 * @package Src\Services\Driver
 */
trait DriverTrait
{
    /**
     * @var string|DriverPattern
     */
    private string|DriverPattern $pattern = 'driverPattern';


    /**
     * @param $driver_id
     * @return mixed
     * @throws BindingResolutionException
     */
    protected function getDriverUpdatedData($driver_id): mixed
    {
        return app()->make($this->pattern)->getDriverUpdatedData($driver_id);
    }

    /**
     * @param  string  $hash
     * @return object|null
     * @throws BindingResolutionException
     */
    protected function getOrderEndPayload(string $hash): ?object
    {
        return app()->make($this->pattern)->getOrderEndPayload($hash);
    }

    /**
     * @param $hash
     * @return array
     * @throws BindingResolutionException
     */
    protected function createOrderInProcessHashes($hash): array
    {
        return app()->make($this->pattern)->createOrderInProcessHashes($hash);
    }

    /**
     * @param $driver_id
     * @throws BindingResolutionException
     */
    protected function driverLocked($driver_id): void
    {
        app()->make($this->pattern)->driverLocked($driver_id);
    }

    /**
     * @param $hash
     * @param $from_cord
     * @return array|null
     * @throws Exception
     */
    protected function driverAcceptedOrder($hash, $from_cord): ?array
    {
        return app()->make($this->pattern)->driverAcceptedOrder($hash, $from_cord);
    }

    /**
     * @param $hash
     * @param $driver_id
     * @return Collection
     * @throws BindingResolutionException
     */
    protected function inStartOrderWithoutData($hash, $driver_id): Collection
    {
        return app()->make($this->pattern)->inStartOrderWithoutData($hash, $driver_id);
    }

    /**
     * @param $shipped
     * @throws BindingResolutionException
     */
    protected function startWithoutDataShippedRoad($shipped): void
    {
        app()->make($this->pattern)->startWithoutDataShippedRoad($shipped);
    }

    /**
     * @param $hash
     * @param $route_id
     * @return Collection|null
     * @throws BindingResolutionException
     */
    protected function inStartOrderWithRoute($hash, $route_id): ?Collection
    {
        return app()->make($this->pattern)->inStartOrderWithRoute($hash, $route_id);
    }

    /**
     * @param $order_id
     * @param $hash
     * @param $driver_id
     * @param $start_cords
     * @param $to_cord
     * @return Collection|null
     * @throws BindingResolutionException
     * @throws JsonException
     * @throws Lexcept
     */
    protected function inStartOrderWithCords($order_id, $hash, $driver_id, $start_cords, $to_cord): ?Collection
    {
        return app()->make($this->pattern)->inStartOrderWithCords($order_id, $hash, $driver_id, $start_cords, $to_cord);
    }

    /**
     * @param $hash
     * @param $order_id
     * @param $driver_id
     * @param $start_cord
     * @param $to_cord
     * @return array
     * @throws BindingResolutionException
     * @throws JsonException
     * @throws Lexcept
     * @throws BindingResolutionException
     */
    protected function priceReCalculate($hash, $order_id, $driver_id, $start_cord, $to_cord): array
    {
        return app()->make($this->pattern)->priceReCalculate($hash, $order_id, $driver_id, $start_cord, $to_cord);
    }

    /**
     * @param  int  $driver_id
     * @param  int  $value
     * @param  string  $type
     * @throws BindingResolutionException
     * @throws JsonException
     */
    protected function toggleClassOptions(int $driver_id, int $value, string $type): void
    {
        app()->make($this->pattern)->toggleClassOptions($driver_id, $value, $type);
    }

    /**
     * @param  int  $driver_id
     * @param  int  $order_id
     * @param  string  $accept_hash
     * @param  bool  $accept
     * @return bool|array
     * @throws Exception
     */
    protected function declineCommonOrder(int $driver_id, int $order_id, string $accept_hash, bool $accept): bool|array
    {
        return app()->make($this->pattern)->declineCommonOrder($driver_id, $order_id, $accept_hash, $accept);
    }

    /**
     * @param $driver_id
     * @param $order_id
     * @param $hash
     * @param $shipped_id
     * @return bool|array
     * @throws BindingResolutionException
     */
    #[ArrayShape([
        'reject_rating' => 'int|float',
        'order_id' => 'int',
        'hash' => 'string',
    ])]
    protected function rejectPreorder($driver_id, $order_id, $hash, $shipped_id): bool|array
    {
        return app()->make($this->pattern)->rejectPreorder($driver_id, $order_id, $hash, $shipped_id);
    }

    /**
     * @param $driver_id
     * @param $order_id
     * @param $shipped_id
     * @param $accept_hash
     * @return bool|array
     * @throws Exception
     */
    protected function acceptPreorder($driver_id, $order_id, $shipped_id, $accept_hash): bool|array
    {
        return app()->make($this->pattern)->acceptPreorder($driver_id, $order_id, $shipped_id, $accept_hash);
    }

    /**
     * @param $client
     * @param $driver_id
     * @param $order_id
     * @param $selected_route_id
     * @param $cord
     * @param $hash
     * @throws BindingResolutionException
     * @throws JsonException
     */
    protected function taxiMapDistribution($client, $driver_id, $order_id, $selected_route_id, $cord, $hash): void
    {
        app()->make($this->pattern)->taxiMapDistribution($client, $driver_id, $order_id, $selected_route_id, $cord, $hash);
    }

    /**
     * @param  int  $driver_id
     * @param  int  $order_id
     * @param  string  $hash
     * @param  int  $selected_route_id
     * @return array|null
     * @throws BindingResolutionException
     * @throws JsonException
     * @throws Lexcept
     */
    protected function regularOnWay(int $driver_id, int $order_id, string $hash, int $selected_route_id): ?array
    {
        return app()->make($this->pattern)->regularOnWay($driver_id, $order_id, $hash, $selected_route_id);
    }

    /**
     * @param  int  $order_id
     * @param  int  $driver_id
     * @return false|Carbon
     * @throws Exception
     */
    protected function preOrderDriverAccept(int $order_id, int $driver_id): bool|Carbon
    {
        return app()->make($this->pattern)->preOrderDriverAccept($order_id, $driver_id);
    }

    /**
     * @param $common
     * @param $driver_id
     * @param  bool  $preorder
     * @return array
     * @throws BindingResolutionException
     */
    #[ArrayShape([
        'order_id' => 'mixed',
        'cash' => 'bool',
        'company_name' => 'mixed',
        'rating_rejected' => 'mixed',
        'rating_accepted' => 'mixed',
        'address_from' => 'mixed',
        'cord_from' => 'mixed'
    ])] private function getCommonPayload($common, $driver_id, bool $preorder = false): array
    {
        return app()->make($this->pattern)->getCommonPayload($common, $driver_id, $preorder);
    }
}
