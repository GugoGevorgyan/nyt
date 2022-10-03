<?php

declare(strict_types=1);

namespace Src\Http\Controllers\App;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use ReflectionException;
use Src\Core\Geo\Geo;
use Src\Exceptions\Yandex\GeocodeException;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\App\AddCardRequest;
use Src\Http\Requests\App\ChangePreOrderDateRequest;
use Src\Http\Requests\Client\CreateAddressesRequest;
use Src\Http\Requests\Client\UpdateClientInfoRequest;
use Src\Http\Requests\Client\UpdateClientPassword;
use Src\Http\Resources\Client\AddCardResource;
use Src\Http\Resources\Client\ClientPreOrdersResource;
use Src\Repositories\TemporaryPayCard\TemporaryPayCardContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Payment\PaymentServiceContract;

use function is_array;

/**
 * Class ClientController
 * @package Src\Http\Controllers\ClientWeb
 */
class ClientController extends Controller
{
    /**
     * ClientController constructor.
     * @param  ClientServiceContract  $clientService
     * @param  PaymentServiceContract  $paymentService
     * @param  TemporaryPayCardContract  $temporaryPayCardContract
     */
    public function __construct(
        protected ClientServiceContract $clientService,
        protected PaymentServiceContract $paymentService,
        protected TemporaryPayCardContract $temporaryPayCardContract
    ) {
    }

    /**
     * @return Factory|View
     */
    public function profile(): Factory|View
    {
//        broadcast(new ClientLessonRadiusTaxiEvent(9, 'TAXI ELI LAVA'));
        // orderTypes orderStatuses
        $data = $this->clientService->profile();

        return view('app.profile', ['data' => $data]);
    }

    /**
     * @return ResponseFactory|Response
     */
    public function clientInfo(): Response|ResponseFactory
    {
        $clientInfo = user();

        if ($clientInfo) {
            return response($clientInfo);
        }

        return response('Something get wrong', 400);
    }

    /**
     * @param  Request  $request
     * @return Response|Application|ResponseFactory
     * @throws Exception
     */
    public function getclientPhoneMask(Request $request): Response|Application|ResponseFactory
    {
        $clientCountry = geoip($request->ip())->country;
        $phone_mask = $this->clientService->getClientCompanyMask($clientCountry)->phone_mask;

        return response($phone_mask);
    }

    /**
     * @param  UpdateClientInfoRequest  $request
     * @param $id
     * @return ResponseFactory|Response
     */
    public function updateClientInfo(UpdateClientInfoRequest $request, $id): Response|ResponseFactory
    {
        $clientInfo = $this->clientService->updateClientInfo($request->all(), $id);

        if ($clientInfo) {
            return response($clientInfo, 200);
        }

        return response('Something get wrong', 400);
    }

    /**
     * @return ResponseFactory|Response
     */
    public function getFavoriteDrivers(): Response|ResponseFactory
    {
        $favoriteDrivers = $this->clientService->getFavoriteDrivers();

        if ($favoriteDrivers) {
            return response($favoriteDrivers, 200);
        }

        return response('Something went wrong', 400);
    }

    /**
     * @return ResponseFactory|Response
     */
    public function getCompanies(): Response|ResponseFactory
    {
        $companies = $this->clientService->getCompanies(get_user_id());

        if ($companies) {
            return response($companies, 200);
        }

        return response('Something went wrong', 400);
    }

    /**
     * @return ResponseFactory|Response
     */
    public function getAddresses(): ResponseFactory|Response
    {
        $addresses = $this->clientService->getAddresses(user()->client_id);

        if ($addresses) {
            return response(['client_id' => user()->client_id,'addresses' => $addresses], 200);
        }

        return response('Something get wrong', 400);
    }

    /**
     * @param  Request  $request
     * @return Response|AnonymousResourceCollection|Application|ResponseFactory
     */
    public function getPreOrders(Request $request): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        $pre_orders = $this->clientService->getPreOrders(get_user_id());

        if (!$pre_orders) {
            return response(['message' => 'Not PreOrders'], 500);
        }

        return ClientPreOrdersResource::collection($pre_orders);
    }

    /**
     * @param  ChangePreOrderDateRequest  $request
     * @param  int  $order_id
     * @param  string  $date
     * @return Response|Application|ResponseFactory
     */
    public function changePreOrders(ChangePreOrderDateRequest $request, int $order_id, string $date): Response|Application|ResponseFactory
    {
        $this->clientService->changePrOrderDate($order_id, $date);

        return response(['message' => 'Дата изменена']);
    }

    /**
     * @param  int  $order_id
     * @return Response|Application|ResponseFactory
     */
    public function cancelPreOrder(int $order_id): Response|Application|ResponseFactory
    {
        $pre_order = $this->clientService->cancelPreOrder(get_user_id(), $order_id);

        if (!$pre_order) {
            return \response(['message' => 'SERVER ERROR'], 500);
        }

        return \response(['message' => 'DELETED']);
    }

    /**
     * @param  CreateAddressesRequest  $request
     * @return ResponseFactory|Response
     */
    public function createAddress(CreateAddressesRequest $request): Response|ResponseFactory
    {
        $address = $this->clientService->createAddress($request->all());

        if ($address) {
            return response($address);
        }

        return response('Something went wrong', 400);
    }

    /**
     * @param  Request  $request
     * @return Response|Application|ResponseFactory
     */
    public function makeAddressFavorite(Request $request): Response|Application|ResponseFactory
    {
        $address = $this->clientService->updateAddressFavorite($request->address, $request->address['client_address_id']);

        if ($address) {
            return response($address);
        }

        return response('Something went wrong', 400);
    }

    /**
     * @param  Request  $request
     * @return ResponseFactory|Response
     */
    public function updateAddress(Request $request): Response|ResponseFactory
    {
        $address = $this->clientService->updateAddress($request->address, $request->client_address_id);

        if ($address) {
            return response($address);
        }

        return response('Something went wrong', 400);
    }

    /**
     * @param $client_id
     * @param $client_address_id
     * @return ResponseFactory|Response
     */
    public function addressDelete($client_id, $client_address_id): Response|ResponseFactory
    {
        $result = $this->clientService->deleteAddress($client_id, $client_address_id);

        if (!$result) {
            return response('Something went wrong', 400);
        }

        return response('Address deleted successful');
    }

    /**
     * @param  AddCardRequest  $request
     * @return AddCardResource
     */
    public function addCard(AddCardRequest $request): AddCardResource
    {
        $acquiring_data = $this->paymentService->addCardForClient($request->validated());

        $this->temporaryPayCardContract->insert([
            'owner_id' => get_user_id(),
            'owner_type' => $this->clientContract->getMap(),
            'transaction_id' => $acquiring_data['transaction_id']
        ]);

        return new AddCardResource($acquiring_data);
    }

    /**
     * @param  UpdateClientPassword  $request
     * @return Response|Application|ResponseFactory
     */
    public function updateClientPassword(UpdateClientPassword $request): Response|Application|ResponseFactory
    {
        $check = Hash::check($request->currentPassword, user()->password);

        if ($check) {
            if ($request->confirmPassword === $request->newPassword) {
                $data = [
                    'password' => Hash::make($request->newPassword)
                ];
                $this->clientService->updatePassword($request->client_id, $data);

                return response('Password changed successful');
            }

            return response('Please make sure your password match');
        }

        return response('Current password is invalid', 401);
    }

    /**
     * @param  Request  $request
     * @return Response|Application|ResponseFactory
     */
    public function addClientPassword(Request $request): Response|Application|ResponseFactory
    {
        if ($request->confirmPassword === $request->newPassword) {
            $data = [
                'email' => $request->email,
                'password' => Hash::make($request->newPassword)
            ];
            $this->clientService->addPassword($request->client_id, $data);
            return response([
                'email' => $request->email,
                'message' => 'Password added successful'
            ], 200);
        }

        return response('Please make sure your password match', 401);
    }

    /**
     * @return string
     */
    public function getClientCreatedInfo(): string
    {
        return user()->created_at->format('Y-m-d');
    }

    /**
     * @param $address
     * @param  Geo  $geo
     * @return Application|ResponseFactory|Response
     * @throws GeocodeException|GuzzleException|ReflectionException|ReflectionException
     */
    public function getCoordinates($address, Geo $geo): Response|Application|ResponseFactory
    {
        $geoObject = $geo->geocode($address);


        if (isset($geoObject['GeoObjectCollection']['featureMember'][0]) && is_array($geoObject)) {
            $coords = $geoObject
            ['GeoObjectCollection']
            ['featureMember'][0]
            ['GeoObject']
            ['Point']
            ['pos'];

            $coordsParts = explode(' ', $coords);

            return response(['lat' => $coordsParts[0], 'lut' => $coordsParts[1]], 200);
        }

        return response(['coords' => null], 200);
    }
}
