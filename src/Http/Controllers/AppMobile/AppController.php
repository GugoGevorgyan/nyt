<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AppMobile;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use ReflectionException;
use Src\Core\Complex\GetRightYKey;
use Src\Core\Enums\ConstApiKey;
use Src\Core\Enums\ConstRedis;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\ClientMobile\AddFavoriteAddressRequest;
use Src\Http\Requests\ClientMobile\EditAddressRequest;
use Src\Http\Requests\ClientMobile\EditSettingsRequest;
use Src\Http\Requests\ClientMobile\GetFavoriteAddressRequest;
use Src\Http\Requests\ClientMobile\GetOrderPriceRequest;
use Src\Http\Requests\ClientMobile\OpenAppRequest;
use Src\Http\Resources\Client\StationResource;
use Src\Http\Resources\ClientMobile\GetAddressResource;
use Src\Http\Resources\ClientMobile\GetOrderPriceResource;
use Src\Http\Resources\ClientMobile\GetSettingsResource;
use Src\Http\Resources\ClientMobile\IniOpenResource;
use Src\Http\Resources\ClientMobile\RealStateResource;
use Src\Repositories\Client\ClientContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\Region\RegionServiceContract;
use Src\ServicesCrud\Order\OrderCrudContract;

/**
 * Class AppController
 * @package Src\Http\Controllers\AppMobile
 */
class AppController extends Controller
{
    /**
     * AppController constructor.
     * @param  ClientServiceContract  $clientService
     * @param  OrderServiceContract  $orderService
     * @param  OrderCrudContract  $orderCrud
     * @param  ClientContract  $clientContract
     * @param  RegionServiceContract  $regionService
     */
    public function __construct(
        protected ClientServiceContract $clientService,
        protected OrderServiceContract $orderService,
        protected OrderCrudContract $orderCrud,
        protected ClientContract $clientContract,
        protected RegionServiceContract $regionService
    ) {
    }

    /**
     * @param  OpenAppRequest  $request
     * @return IniOpenResource
     */
    public function open(OpenAppRequest $request): IniOpenResource
    {
        $lat = $request->validated()['lat'];
        $lut = $request->validated()['lut'];

        $result = $this->clientService->openMobileApp(user(), compact('lat', 'lut'));

        return (new IniOpenResource($result))->additional(['message' => 'ok']);
    }

    /**
     * @param  GetOrderPriceRequest  $request
     * @return Application|ResponseFactory|AnonymousResourceCollection|Response
     */
    public function getOrderPrice(GetOrderPriceRequest $request): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        $options = [
            'payment_type' => (int)$request->validated()['payment']['type'],
            'payment_type_company' => (int)$request->validated()['payment']['company'],
            'car_class' => (int)$request->validated()['car']['class'],
            'demands' => $request->validated()['car']['options'],
            'time' => $request->validated()['time']['time'],
            'rent_time' => $request->validated()['rent_time'] ?? null
        ];

        $price_data = $this->orderService->orderFromToPrices($request->user(), $request->route, $options, $request->time, $request->validated()['is_rent']);

        if (!$price_data) {
            return response(['message' => 'Ошибка Расчета', 'status' => 'FAILED'], 500);
        }

        redis()->hset(ConstRedis::order_calc_request(get_user_id()), 'init_coin', igbinary_serialize($request->validated()));
        redis()->expire(ConstRedis::order_calc_request(get_user_id()), 7200 * 60);

        return GetOrderPriceResource::collection($price_data)->additional(['status' => 'ok', 'message' => 'Calculated Price Data']);
    }

    /**
     * @return Application|ResponseFactory|Response|RealStateResource
     */
    public function getState(): RealStateResource|Response|Application|ResponseFactory
    {
        $result = $this->clientService->getRealState(user());

        if (!$result) {
            return response(['message' => 'failed'], 500);
        }

        return new RealStateResource($result);
    }

    /**
     * @param  GetFavoriteAddressRequest  $request
     * @return Application|ResponseFactory|AnonymousResourceCollection|Response
     */
    public function getFavoriteAddress(GetFavoriteAddressRequest $request): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        $user_id = get_user_id();

        $address = $this->clientService->getClientAddresses($user_id, (boolean)$request->favorite);

        if (!$address) {
            return response(['message' => 'You are not a addresses'], 400);
        }

        return GetAddressResource::collection($address);
    }

    /**
     * @param  AddFavoriteAddressRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function addAddress(AddFavoriteAddressRequest $request): Response|Application|ResponseFactory
    {
        $client_id = get_user_id();

        $id = $this->clientService->addClientAddresses($client_id, $request->payload);

        return response(['message' => 'Created', 'payload' => ['address_id' => $id]]);
    }

    /**
     * @param  EditAddressRequest  $request
     * @param $address_id
     * @return Application|ResponseFactory|Response
     */
    public function editAddress(EditAddressRequest $request, $address_id): Response|Application|ResponseFactory
    {
        $client_id = get_user_id();

        $this->clientService->editClientAddress($client_id, $address_id, $request->payload);

        return response(['message' => 'Address updated']);
    }

    /**
     * @param $address_id
     * @return Application|ResponseFactory|Response
     */
    public function deleteAddress($address_id): Response|Application|ResponseFactory
    {
        $client_id = get_user_id();

        $this->clientService->deleteClientAddress($client_id, $address_id);

        return response(['message' => 'Address deleted']);
    }

    /**
     * @return GetSettingsResource
     */
    public function getSettings(): GetSettingsResource
    {
        $settings = $this->clientService->getSettings(get_user_id());

        return new GetSettingsResource($settings);
    }

    /**
     * @param  EditSettingsRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function editSettings(EditSettingsRequest $request): Response|Application|ResponseFactory
    {
        $this->clientService->editSettings(get_user_id(), $request->validated());

        return response(['message' => 'updated']);
    }

    /**
     * @param  Request  $request
     * @param  null  $city
     * @return StationResource
     * @throws Exception
     */
    public function getTransportsStations(Request $request, $city = null): StationResource
    {
        $city = !$city ? geoip()->getLocation($request->ip())->city : $city;
        $data = $this->regionService->getTransportsPoints($city);

        return new StationResource($data);
    }

    /**
     * @param  int  $type
     * @param  string|null  $old_key
     * @return Application|ResponseFactory|Response
     * @throws ReflectionException
     */
    public function getApiKeys(int $type, string $old_key = null): Response|Application|ResponseFactory
    {
        $key = array_search($type, ConstApiKey::getAll(), true);
        $key_type = ConstApiKey::$key()->getValue();
        $new_key = GetRightYKey::complex($key_type, $old_key);

        return response(['message' => 'new key passed', '_payload' => ['key' => Str::before(Str::after($new_key, 'apikey='), '&')]]);
    }
}
