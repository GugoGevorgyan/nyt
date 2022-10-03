<?php

declare(strict_types=1);


namespace Src\Services\CorporateClient;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use ServiceEntity\BaseService;
use Src\Models\Corporate\AdminCorporate;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\ClientAddress\ClientAddressContract;
use Src\Repositories\Company\CompanyContract;
use Src\Repositories\CorporateClient\CorporateClientContract;

/**
 * Class CorporateClientService
 * @package Src\Services\CorporateClient
 */
class CorporateClientService extends BaseService implements CorporateClientServiceContract
{
    /**
     * @param  CorporateClientContract  $corporateClientContract
     * @param  ClientContract  $clientContract
     * @param  CompanyContract  $companyContract
     * @param  ClientAddressContract  $clientAddressContract
     */
    public function __construct(
        protected CorporateClientContract $corporateClientContract,
        protected ClientContract $clientContract,
        protected CompanyContract $companyContract,
        protected ClientAddressContract $clientAddressContract
    ) {
    }

    /**
     * @inheritDoc
     */
    public function index(AdminCorporate $admin_corporate, array $request)
    {
        $company = $this->companyContract->find($admin_corporate->company_id, ['company_id']);


        $per_page = $request['per-page'] ?: 10;
        $page = $request['page'] ?: 1;
        $search = (isset($request['search']) && 'null' !== $request['search']) ? $request['search'] : null;

        return $this->corporateClientContract
            ->where('company_id', '=', $company->company_id ?? null)
            ->when(
                $search,
                fn(Builder $query) => $query
                    ->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('client', fn(Builder $query) => $query
                        ->whereRaw("CONCAT(surname, ' ', name, ' ', phone) LIKE '%".$search."%'")
                    )
                    ->orWhere('surname', 'LIKE', '%'.$search.'%')
                    ->orWhere('patronymic', 'LIKE', '%'.$search.'%')
            )
            ->with([
                'client' => fn($query) => $query->select('*'),
                'client_addresses' => fn($query) => $query->select(['*']),
                'current_order'
            ])->orderBy('corporate_client_id', 'desc')
            ->findAll(['*'])
            ->filter(fn($item) => $item->company_id === $company->company_id)
            ->paginate($per_page, 'page', $page);
    }

    /**
     * @inheritDoc
     */
    public function isClientAttached($client_id)
    {
        $company = $this->companyContract->find(user()->company_id);

        return $this->corporateClientContract
            ->where('company_id', '=', $company->company_id)
            ->where('client_id', '=', $client_id)
            ->findFirst();
    }

    /**
     * @param $form_data
     * @param $client_id
     * @return bool|object|null
     * @throws Exception
     */
    public function updateClient($form_data, $client_id)/*: ?object*/
    {
        $user = user();

        $company = $this->companyContract->find($user->company_id);

        $corporate_client = $this->corporateClientContract
            ->where('company_id', '=', $company->company_id)
            ->where('client_id', '=', $client_id)
            ->findFirst(['company_id', 'corporate_client_id', 'client_id']);

        if (!$corporate_client) {
            return $this->createClient($form_data);
        }

        $this->corporateClientContract->beginTransaction(fn() => [
            $this->corporateClientContract->forgetCache(),

            $this->corporateClientContract->update($corporate_client['corporate_client_id'], [
                'car_classes_ids' => ['ids' => isset($form_data['car_classes']) ? array_map('\intval', (array)$form_data['car_classes']) : []],
                'name' => $form_data['name'],
                'surname' => $form_data['surname'],
                'patronymic' => $form_data['patronymic'],
                'allow_weekends' => $form_data['allow_weekends'],
                'allow_order' => $form_data['allow_order'],
                'limit' => $form_data['limit'],
            ]),
        ]);

        return true;
    }

    /**
     * @param $form_data
     * @return object|null
     * @throws Exception
     */
    public function createClient($form_data): ?object
    {
        $user = Auth::user();

        $client_data = [
            'name' => $form_data['name'],
            'surname' => $form_data['surname'],
            'patronymic' => $form_data['patronymic'],
            'phone' => $form_data['phone']
        ];

        $company = $this->companyContract->find($user->company_id);

        return $this->corporateClientContract->beginTransaction(function () use ($client_data, $form_data, $company) {
            $this->corporateClientContract->forgetCache();

            if (!$form_data['client_id'] || !$form_data['patronymic']) {
                $client = $this->clientContract->create($client_data);
            } else {
                $client = $this->clientContract->find($form_data['client_id']);
            }

            $client_id = !$form_data['client_id'] ? $client->client_id : $form_data['client_id'];
            $client_name = !$form_data['name'] ? $client->name : $form_data['name'];
            $client_surname = !$form_data['surname'] ? $client->surname : $form_data['surname'];
            $client_patronymic = !$form_data['patronymic'] ? $client->patronymic : $form_data['patronymic'];

            return $this->corporateClientContract->updateOrCreate(['client_id', '=', $client_id, 'company_id', '=', $company->company_id],
                [
                    'client_id' => $client_id,
                    'company_id' => $company->company_id,
                    'car_classes_ids' => [
                        'ids' => \is_array($form_data['car_classes']) ? array_map('\intval', $form_data['car_classes']) : $form_data['car_classes']
                    ],
                    'name' => $client_name,
                    'surname' => $client_surname,
                    'patronymic' => $client_patronymic,
                    'allow_weekends' => $form_data['allow_weekends'],
                    'allow_order' => $form_data['allow_order'],
                    'limit' => $form_data['limit'],
                ]
            );
        });
    }

    /**
     * @param $ids
     * @return bool|mixed
     * @throws Exception
     */
    public function deleteClient($ids): bool
    {
        $user = Auth::user();
        $company = $this->companyContract->find($user->company_id);

        return $this->corporateClientContract->beginTransaction(function () use ($company, $ids) {
            $this->corporateClientContract->forgetCache();
            $clients = $this->corporateClientContract
                ->where('company_id', '=', $company->company_id)
                ->whereIn('corporate_client_id', $ids)
                ->findAll();

            if ($clients->isEmpty()) {
                return false;
            }

            foreach ($clients as $iValue) {
                $iValue->delete();
            }

            return true;
        });
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function getClient($id)
    {
        $user = Auth::user();
        $company = $this->companyContract->find($user->company_id);
        $corporateClient = $this->corporateClientContract->find($id);

        if (!empty($corporateClient) && $corporateClient->company_id == $company->company_id) {
            return $corporateClient->load(
                [
                    'client' => fn($query) => $query->with('corporateOrders')->get()
                ]
            );
        }

        return false;
    }

    public function getCorporateClientData($client_id, $company_id)
    {
        return $this->corporateClientContract->where('client_id', '=', $client_id)
            ->where('company_id', '=', $company_id)
            ->findFirst();
    }

    /**
     * @param $data
     * @return object|null
     */
    public function createAddress($data): ?object
    {
        return $this->clientAddressContract->create($data);
    }

    /**
     * @param $data
     * @param $address_id
     * @return mixed|null
     */
    public function updateAddress($data, $address_id)
    {
        $address = $this->clientAddressContract->find($address_id);

        if (!$address) {
            return null;
        }

        $this->clientAddressContract->update($address_id, $data);

        return $address;
    }

    /**
     * @param $address_id
     * @return mixed
     */
    public function deleteAddress($address_id)
    {
        $address = $this->clientAddressContract->find($address_id);
        return $address->delete();
    }
}
