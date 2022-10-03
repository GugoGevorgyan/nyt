<?php

declare(strict_types=1);


namespace Src\Services\Client;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use ServiceEntity\Contract\BaseContract;
use Src\Models\Client\BeforeAuthClient;
use Src\Models\Client\Client;
use Src\Repositories\BeforeAuthClient\BeforeAuthClientContract;
use Src\Repositories\Client\ClientContract;

/**
 * Interface ClientServiceContract
 * @package Src\Services\ClientMessage
 */
interface ClientServiceContract extends BaseContract
{

    /**
     * @return mixed
     */
    public function profile();

    /**
     * @param $request
     * @return object|null
     */
    public function createClientCallCenter($request): ?object;

    /**]
     * @param $client_id
     * @param $request
     * @return mixed
     */
    public function updateClientCallCenter($client_id, $request);

    /**
     * @param  array  $request
     * @return LengthAwarePaginator
     */
    public function getOrders(array $request): LengthAwarePaginator;

    /**
     * @param $client_id
     * @param $skip
     * @param $take
     * @return mixed
     */
    public function getClientMobileOrders($client_id, $skip, $take): \Illuminate\Support\Collection;

    /**
     * @param $client_id
     * @return Collection
     */
    public function getFavoriteDrivers($client_id): Collection;


    /**
     * @param $data
     * @param $client_id
     * @return mixed
     */
    public function updateClientInfo($data, $client_id);

    /**
     * @param $client_id
     * @return mixed
     */
    public function getCompanies($client_id);

    /**
     * @param $client_id
     * @return mixed
     */
    public function getAddresses($client_id);

    /**
     * @param $phone
     * @return array
     */
    public function callCenterCheckPassengerExists($phone): array;

    /**
     * @param $user
     * @param  array  $cord
     * @return array
     */
    public function getOrderInfo($user, array $cord = []): array;

    /**
     * @param  Model  $user
     * @param  null  $country_lat
     * @param  null  $lut
     * @return null|string
     */
    public function getPhoneMask(Model $user, $country_lat = null, $lut = null): ?string;

    /**
     * @param $address
     * @return mixed
     */
    public function createAddress($address);

    /**
     * @param $address
     * @param $id
     * @return mixed
     */
    public function updateAddress($address, $id);

    /**
     * @param $address
     * @param $client_address_id
     * @return mixed
     */
    public function updateAddressFavorite($address, $client_address_id);

    /**
     * @param $client_id
     * @param $client_address_id
     * @return mixed
     */
    public function deleteAddress($client_id, $client_address_id);

    /**
     * @param $phone
     * @return mixed
     */
    public function createClient($phone);

    /**
     * @param $phone
     * @param  array|string[]  $values
     * @return mixed
     */
    public function getClientByPhone($phone, array $values = ['*']);

    /**
     * @param $client_id
     * @param  array  $values
     * @return mixed
     */
    public function getClientById($client_id, array $values = []);

    /**
     * @param $phone
     * @return array
     */
    public function callCenterCheckClientExists($phone): array;

    /**
     * @param $order_id
     * @param  array  $values
     * @return object|null
     */
    public function getOrderedClientData($order_id, array $values = ['*']): ?object;

    /**
     * @param $client_id
     * @param  string  $client_type
     * @param  int|null  $order_id
     * @return array
     */
    public function getCorrectCoordinate($client_id, string $client_type = "client", int $order_id = null): ?array;

    /**
     * @param $type
     * @return BeforeAuthClientContract|ClientContract|mixed
     */
    public function getClientContract($type);

    /**
     * @param $type
     * @return bool
     */
    public function clientIsAuth($type): ?bool;

    /**
     * @param  Model  $client
     * @param $status
     * @return mixed
     */
    public function clientOnlineMaster(Model $client, $status);

    /**
     * @param  Client  $client
     * @param  array  $cords
     * @return array
     */
    public function openMobileApp(Client $client, array $cords): array;

    /**
     * @param $client_id
     * @return mixed
     */
    public function getClientCompanies($client_id);

    /**
     * @param  Client|BeforeAuthClient|Model  $client
     * @return null|array
     */
    public function getRealState($client): ?array;

    /**
     * @param $client_id
     * @return bool
     */
    public function getPreorderLimit($client_id): bool;

    /**
     * @param $client_id
     * @param  bool  $show
     * @param  array  $cord
     * @return void
     */
    public function showMyCordsInOrder($client_id, bool $show = false, array $cord = []): void;

    /**
     * @param $client_id
     * @return int|null
     */
    public function hasOrderWithoutAssessment($client_id): bool|int;

    /**
     * @param $order_id
     * @param $client_id
     * @return object|null
     */
    public function getOrderDetail($order_id, $client_id): ?object;

    /**
     * @param  int  $client_id
     * @param  bool  $favorite
     * @return \Illuminate\Support\Collection
     */
    public function getClientAddresses(int $client_id, bool $favorite = false): \Illuminate\Support\Collection;

    /**
     * @param  int  $client_id
     * @param  array  $payload
     * @return int|null
     */
    public function addClientAddresses(int $client_id, array $payload): ?int;

    /**
     * @param $client_id
     * @param $address_id
     * @param $payload
     * @return mixed
     */
    public function editClientAddress($client_id, $address_id, $payload): void;

    /**
     * @param $client_id
     * @param $address_id
     */
    public function deleteClientAddress($client_id, $address_id): void;

    /**
     * @param $client_id
     * @return mixed
     */
    public function getSettings($client_id);

    /**
     * @param $client_id
     * @param  array  $settings
     * @return mixed
     */
    public function editSettings($client_id, array $settings): void;

    /**
     * @param $client_id
     * @param $order_id
     * @return bool
     */
    public function deletePreorder($client_id, $order_id): bool;

    /**
     * @param $client_id
     * @param $data
     * @return mixed
     */
    public function updatePassword($client_id, $data);

    /**
     * @param $client_id
     * @param $data
     * @return mixed
     */
    public function addPassword($client_id, $data);

    /**
     * @param $clientCountry
     * @return mixed
     */
    public function getClientCompanyMask($clientCountry);

    /**
     * @param $client_id
     * @param  int|null  $skip
     * @param  int|null  $take
     * @return \Illuminate\Support\Collection
     */
    public function getPreOrders($client_id, int $skip = null, int $take = null): \Illuminate\Support\Collection;

    /**
     * @param $client_id
     * @param $order_id
     * @return bool
     */
    public function cancelPreOrder($client_id, $order_id): bool;

    /**
     * @param $order_id
     * @param $date
     * @return bool
     */
    public function changePrOrderDate($order_id, $date): bool;
}
