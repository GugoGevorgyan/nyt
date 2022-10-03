<?php

declare(strict_types=1);


namespace Src\Services\Order;


use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Trait OrderTrait
 * @package Src\Services\Order
 */
trait OrderTrait
{
    /**
     * @var string|OrderPattern
     * @link OrderPattern
     */
    private string|OrderPattern $pattern = 'orderPattern';

    /**
     * @param $cord
     * @param $options
     * @param  bool  $rent
     * @return array|null
     * @throws BindingResolutionException
     */
    protected function existedPriceRequest($cord, $options, bool $rent = false): ?array
    {
        return app()->make($this->pattern)->existedPriceRequest($cord, $options, $rent);
    }

    /**
     * @param  array  $existed_price
     * @param  array  $prices
     * @param  array  $options
     * @return array|null
     * @throws BindingResolutionException
     */
    protected function reCalcOptions(array $existed_price, array $prices, array $options): ?array
    {
        return app()->make($this->pattern)->reCalcOptions($existed_price, $prices, $options);
    }

    /**
     * @param $request
     * @param $order
     * @return bool
     * @throws BindingResolutionException
     */
    protected function callCenterDriverFeedbackCreate($request, $order): bool
    {
        return app()->make($this->pattern)->callCenterDriverFeedbackCreate($request, $order);
    }

    /**
     * @param $request
     * @param $order
     * @return bool
     * @throws BindingResolutionException
     */
    protected function callCenterClientFeedbackCreate($request, $order): bool
    {
        return app()->make($this->pattern)->callCenterClientFeedbackCreate($request, $order);
    }

    /**
     * @param $request
     * @param $order
     * @return bool
     * @throws BindingResolutionException
     */
    protected function callCenterWorkerComplaintCreate($request, $order): bool
    {
        return app()->make($this->pattern)->callCenterWorkerComplaintCreate($request, $order);
    }

    /**
     * @param  array  $fields
     * @return array
     * @throws BindingResolutionException
     */
    protected function getPrice(array $fields): array
    {
        return app()->make($this->pattern)->getPrice($fields);
    }

    /**
     * @return string[]
     * @throws BindingResolutionException
     */
    protected function viewOrderRelations(): array
    {
        return app()->make($this->pattern)->viewOrderRelations();
    }

    /**
     * @return string[]
     * @throws BindingResolutionException
     */
    protected function dispatcherOrderRelations(): array
    {
        return app()->make($this->pattern)->dispatcherOrderRelations();
    }

    /**
     * @return string[]
     * @throws BindingResolutionException
     */
    protected function operatorOrderRelations(): array
    {
        return app()->make($this->pattern)->operatorOrderRelations();
    }

    /**
     * @param $order_id
     * @return string
     * @throws BindingResolutionException
     */
    protected function orderDeliveryTime($order_id): string
    {
        return app()->make($this->pattern)->orderDeliveryTime($order_id);
    }

    /**
     * @param $price
     * @param $car_option_id
     * @return array
     * @throws BindingResolutionException
     */
    private function filterOptionsDiffData($price, $car_option_id): array
    {
        return app()->make($this->pattern)->filterOptionsDiffData($price, $car_option_id);
    }
}
