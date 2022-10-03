<?php

declare(strict_types=1);

namespace Src\Http\Controllers\DriverApi;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use JsonException;
use ReflectionException;
use Src\Core\Complex\GetRightYKey;
use Src\Core\Enums\ConstApiKey;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\Driver\AddFavoriteAddressRequest;
use Src\Http\Requests\Driver\CancelCommonOrderRequest;
use Src\Http\Requests\Driver\ChangeOnlineRequest;
use Src\Http\Requests\Driver\OrderPauseRequest;
use Src\Http\Requests\Driver\OrderReadyRequest;
use Src\Http\Requests\Driver\ProfileImageUploadRequest;
use Src\Http\Requests\Driver\ProfileInitialInfoRequest;
use Src\Http\Requests\Driver\SelectFavoriteRequest;
use Src\Http\Requests\Driver\ToggleCarClassRequest;
use Src\Http\Requests\Driver\ToggleCarOptionRequest;
use Src\Http\Requests\Driver\UpdateProfileRequest;
use Src\Http\Resources\Client\StationResource;
use Src\Http\Resources\Driver\AddFavoriteAddressResource;
use Src\Http\Resources\Driver\CommonOrdersResource;
use Src\Http\Resources\Driver\DriverResource;
use Src\Http\Resources\Driver\RealStateResource;
use Src\Services\Auth\AuthService;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\Region\RegionServiceContract;
use Src\ServicesCrud\Driver\DriverCrudContract;
use Src\ServicesCrud\Order\OrderCrudContract;

/**
 * Class DriverController
 * @package Src\Http\Controllers\DriverApi
 */
class DriverController extends Controller
{
    /**
     * @param  DriverServiceContract  $driverService
     * @param  AuthService  $authService
     * @param  OrderCrudContract  $orderCrud
     * @param  DriverCrudContract  $driverCrud
     * @param  OrderServiceContract  $orderService
     * @param  RegionServiceContract  $regionService
     * @param  GeocodeServiceContract  $geoService
     */
    public function __construct(
        protected DriverServiceContract $driverService,
        protected AuthService $authService,
        protected OrderCrudContract $orderCrud,
        protected DriverCrudContract $driverCrud,
        protected OrderServiceContract $orderService,
        protected RegionServiceContract $regionService,
        protected GeocodeServiceContract $geoService
    ) {
    }

    /**
     * @param  OrderReadyRequest  $request
     * @return JsonResponse
     */
    public function driverReady(OrderReadyRequest $request): JsonResponse
    {
        $is_ready = $this->driverService->driverOrderReady(get_user_id(), $request->validated());

        if (!$is_ready) {
            return jsponse(['message' => trans('messages.order_ready.non_ready'), 'statusCode' => 500], 500);
        }

        $common_orders = $this->driverService->getBelatedCommons(get_user_id());

        return jsponse([
            'message' => trans('messages.order_ready.ready'),
            'statusCode' => 201,
            'ready' => (bool)$request->ready,
            'commons' => CommonOrdersResource::collection($common_orders)
        ]);
    }

    /**
     * @param  UpdateProfileRequest  $request
     * @return Application|ResponseFactory|Response|DriverResource
     */
    public function updateProfile(UpdateProfileRequest $request): Response|DriverResource|Application|ResponseFactory
    {
        if ((int)$request->driver_id !== (int)get_user_id()) {
            return response(['message' => 'forbidden'], 403);
        }

        $driver = $this->driverCrud->updateProfile($request);

        if (!$driver) {
            return response(['message' => 'Driver profile not updated'], 500);
        }

        $driver->loadMissing(['current_franchise', 'franchisee.regions', 'car']);

        return (new DriverResource($driver))->additional(['message' => 'Driver profile updated']);
    }

    /**
     * @param $driver_id
     * @return Application|ResponseFactory|Response
     */
    public function delete($driver_id): Response|Application|ResponseFactory
    {
        if ((int)$driver_id !== (int)get_user_id()) {
            return response(['message' => 'forbidden'], 403);
        }

        $driver = $this->driverService->deleteDriver($driver_id);

        return ($driver)
            ? response(['message' => 'Driver deleted !'])
            : response(['message' => 'Driver did not deleted !'], 400);
    }

    /**
     * @param  AddFavoriteAddressRequest  $request
     * @return Application|ResponseFactory|Response|AddFavoriteAddressResource
     * @throws JsonException
     */
    public function addFavoriteAddress(AddFavoriteAddressRequest $request): AddFavoriteAddressResource|Response|Application|ResponseFactory
    {
        $create_address = $this->driverCrud->addFavoriteAddress(
            user()->{user()->getKeyName()},
            $request->address,
            $request->lat,
            $request->lut,
            $request->target
        );

        if (!$create_address) {
            return response(['message' => 'Failed'], 500);
        }

        return new AddFavoriteAddressResource($create_address);
    }

    /**
     * @param  SelectFavoriteRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function selectFavorite(SelectFavoriteRequest $request): Response|Application|ResponseFactory
    {
        $select_address = $this->driverCrud->selectAddress(user()->{user()->getKeyName()}, $request->address_id, $request->cords);

        if (!$select_address) {
            return response(['message' => 'Failed'], 500);
        }

        if ('deactivate' === $select_address) {
            return response(['message' => trans('messages.driver_select_favorite_address_deactivate'), 'status' => 'ok']);
        }

        return response(['message' => trans('messages.driver_select_favorite_address'), 'status' => 'ok']);
    }

    /**
     * @return Application|ResponseFactory|Response|RealStateResource
     */
    public function state(): Response|RealStateResource|Application|ResponseFactory
    {
        $real_state = $this->driverService->getRealState(get_user_id());
        $keys = $this->geoService->getYKeys();

        if (!$real_state) {
            return response(['message' => 'FAILED'], 500);
        }

        $data = array_merge($real_state, ['keys' => $keys]);

        return (new RealStateResource($data))->additional(['message' => 'OK']);
    }

    /**
     * @param  OrderPauseRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function orderPause(OrderPauseRequest $request): Response|Application|ResponseFactory
    {
        $result = $this->orderService->driverOrderPause($request->user()->{$request->user()->getKeyName()}, $request->hash);

        return response(['message' => $result, 'status' => 'OK']);
    }

    /**
     * @param  ProfileImageUploadRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function profileImageUpload(ProfileImageUploadRequest $request): Response|Application|ResponseFactory
    {
        $this->driverService->profileImageUpload($request->user()->driver_id, $request->file('photo'));

        return response(['message' => 'Image uploaded', 'status' => 'OK']);
    }

    /**
     * @param  ToggleCarClassRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function toggleCarClass(ToggleCarClassRequest $request): Response|Application|ResponseFactory
    {
        $this->driverService->toggleCarClass(get_user_id(), (int)$request->class_id);
        return response(['message' => 'classes updated', 'status' => 'ok']);
    }

    /**
     * @param  ToggleCarOptionRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function toggleCarOption(ToggleCarOptionRequest $request): Response|Application|ResponseFactory
    {
        $this->driverService->toggleCarOption(get_user_id(), (int)$request->option_id);
        return response(['message' => 'classes updated', 'status' => 'ok']);
    }

    /**
     * @param  ProfileInitialInfoRequest  $request
     * @return array
     */
    public function profileInitialInfo(ProfileInitialInfoRequest $request): array
    {
        return $this->driverService->getProfileInitialData(get_user_id());
    }

    /**
     * @param  ChangeOnlineRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function changeOnline(ChangeOnlineRequest $request): Response|Application|ResponseFactory
    {
        $result = $this->driverService->changeOnlineStatus(get_user_id(), $request->online);

        if (!$result) {
            return response(['message' => 'error when updated online status response data', 500]);
        }

        return response(['message' => 'ok', 'online' => $request->online]);
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

    /**
     * @return AnonymousResourceCollection
     */
    public function getCommonOrders(): AnonymousResourceCollection
    {
        $commons = $this->driverService->getCommonOrders(get_user_id());

        return CommonOrdersResource::collection($commons);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getCommonArmorsOrders(): AnonymousResourceCollection
    {
        $commons_armors = $this->driverService->getCommonArmorsOrders(get_user_id());

        return CommonOrdersResource::collection($commons_armors);
    }

    /**
     * @param  CancelCommonOrderRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function commonOrderCancel(CancelCommonOrderRequest $request): Response|Application|ResponseFactory
    {
        $cancel = $this->driverService->cancelCommonOrder(get_user_id(), $request->order_id);

        if (!$cancel) {
            return response(['message' => trans('messages.messages.order_no_canceled')], 500);
        }

        return response(['message' => trans('messages.order_canceled')]);
    }
}
