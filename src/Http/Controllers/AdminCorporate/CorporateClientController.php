<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminCorporate;

use Collective\Annotations\Routing\Annotations\Annotations\Delete;
use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Collective\Annotations\Routing\Annotations\Annotations\Post;
use Collective\Annotations\Routing\Annotations\Annotations\Put;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\AdminCorporate\CorporateClientCreateRequest;
use Src\Http\Requests\AdminCorporate\CorporateClientUpdateRequest;
use Src\Http\Requests\AdminCorporate\GetClientsDataRequest;
use Src\Http\Requests\Client\CreateAddressesRequest;
use Src\Services\CorporateClient\CorporateClientServiceContract;


/**
 * Class CorporateClientController
 * @package Src\Http\Controllers\AdminCorporate
 */
class CorporateClientController extends Controller
{
    /**
     * @var CorporateClientServiceContract
     */
    protected CorporateClientServiceContract $corporateClientService;

    /**
     * CorporateClientController constructor.
     * @param  CorporateClientServiceContract  $corporateClientServiceContract
     */
    public function __construct(CorporateClientServiceContract $corporateClientServiceContract)
    {
        $this->corporateClientService = $corporateClientServiceContract;
    }


    /**
     * @Get("company/clients", as="getClients")
     *
     * @param  GetClientsDataRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function takeClients(GetClientsDataRequest $request)
    {
        $admin = user();

        $clients = $this->corporateClientService->index($admin, $request->validationData());

        if (!$clients) {
            return response(['message' => 'Clients Not Found'], 400);
        }

        return response($clients);
    }

    /**
     * @Post("company/client/create", as="createClient")
     *
     * @param  CorporateClientCreateRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function createCorporateClient(CorporateClientCreateRequest $request)
    {
        $client = $this->corporateClientService->createClient($request->all());

        if (!$client) {
            return response(['message' => 'Oops! Something went wrong.'], 500);
        }

        return response(['message' => 'ClientMessage successfully created']);
    }

    /**
     * @Put("company/client/update/{id}", as="updateClient", no_prefix="true")
     *
     * @param  CorporateClientUpdateRequest  $request
     * @return ResponseFactory|Response
     */
    public function updateCorporateClient(CorporateClientUpdateRequest $request, $id)
    {
        $client = $this->corporateClientService->updateClient($request->all(), $id);

        if (!$client) {
            return response(['message' => 'Oops! Something went wrong.'], 500);
        }

        return response(['message' => 'ClientMessage successfully updated'], 200);
    }

    /**
     * @Delete("company/client/delete", as="deleteClient", no_prefix="true")
     *
     * @param  Request  $request
     * @return mixed
     */
    public function deleteCorporateClient(Request $request)
    {
        $client = $this->corporateClientService->deleteClient($request->ids);

        if (!$client) {
            return response(['message' => 'Oops! Something went wrong.'], 500);
        }

        return response(['message' => 'ClientMessage successfully deleted'], 200);
    }


    /**
     * @Get("company/clients/{id}", as="getClientInfo", where={"id": "[0-9]+"}, no_prefix="true")
     *
     * @param $id
     * @return ResponseFactory|Response
     */
    public function getClient($id)
    {
        $client = $this->corporateClientService->getClient($id);

        if (!empty($client)) {
            return response($client, 400);
        }

        return response('ClientMessage not found', 400);
    }

    /**
     * @Post("company/client/address/create", as="createAddress", no_prefix="true")
     *
     * @param  CreateAddressesRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function createAddress(CreateAddressesRequest $request)
    {
        $data = [
            'client_id' => $request->client_id,
            'name' => $request->name,
            'address' => $request->address,
            'lat' => $request->lat,
            'lut' => $request->lut,
            'driver_hint' => $request->driver_hint,
        ];

        $address = $this->corporateClientService->createAddress($data);

        if (!$address) {
            return response(['message' => 'Oops! Something went wrong.'], 500);
        }

        return response(['message' => 'Address successfully created', 'address' => $address], 200);
    }

    /**
     * @Put("company/client/address/update/{address_id}", as="updatedAddress", where={"address_id": "[0-9]+"},  no_prefix="true")
     *
     * @param  CreateAddressesRequest  $request
     * @param $address_id
     * @return Application|ResponseFactory|Response
     */
    public function updateAddress(CreateAddressesRequest $request, $address_id)
    {
        $address = $this->corporateClientService->updateAddress($request->all(), $address_id);

        if ($address) {
            return response(['message' => 'Address updated successful', 'address' => $address], 200);
        }

        return response(['message' => 'Something went wrong'], 400);
    }

    /**
     * @Delete("company/client/address/delete/{address_id}", as="deleteAddress", no_prefix="true", where={"address_id": "[0-9]+"})
     *
     * @param $address_id
     * @return Application|ResponseFactory|Response
     */
    public function deleteAddress($address_id)
    {
        $result = $this->corporateClientService->deleteAddress($address_id);

        if ($result) {
            return response('Address deleted successful', 200);
        }

        return response('Something went wrong', 400);
    }

}
