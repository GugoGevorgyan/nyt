<?php


namespace Src\Services\CorporateClient;


use ServiceEntity\Contract\BaseContract;
use Src\Models\Corporate\AdminCorporate;

/**
 * Interface CorporateClientServiceContract
 * @package Src\Services\CorporateClient
 */
interface CorporateClientServiceContract extends BaseContract
{
    /**
     * @param  AdminCorporate  $admin_corporate
     * @param  array  $request
     * @return mixed
     */
    public function index(AdminCorporate $admin_corporate, array $request);

    /**
     * @param $form_data
     * @return object|null
     */
    public function createClient($form_data): ?object;

    /**
     * @param $form_data
     * @param $client_id
     * @return object|null
     */
    public function updateClient($form_data, $client_id);

    /**
     * @param $ids
     * @return mixed
     */
    public function deleteClient($ids);

    /**
     * @param $id
     * @return mixed
     */
    public function getClient($id);

    /**
     * @param $data
     * @return object|null
     */
    public function createAddress($data): ?object;

    /**
     * @param $data
     * @param $address_id
     * @return mixed
     */
    public function updateAddress($data, $address_id);

    /**
     * @param $address_id
     * @return mixed
     */
    public function deleteAddress($address_id);

    /**
     * @param $client_id
     * @return mixed
     */
    public function isClientAttached($client_id);

    /**
    * @param $client_id
    * @param $company_id
     */
    public function getCorporateClientData($client_id, $company_id);
}
