<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminCorporate;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\View\View;
use ReflectionException;
use Spatie\Async\Pool;
use Src\Core\Geo\Geo;
use Src\Exceptions\Yandex\GeocodeException;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\AdminCorporate\CheckClientExistsRequest;
use Src\Services\Car\CarServiceContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Company\CompanyServiceContract;
use Src\Services\CorporateClient\CorporateClientServiceContract;
use Src\Services\Order\OrderServiceContract;

use function is_array;

/**
 * Class IndexController
 * @package Src\Http\Controllers\PersonalAdmin
 */
class IndexController extends Controller
{
    /**
     * CorporateClientController constructor.
     * @param  CompanyServiceContract  $companyServiceContract
     * @param  CarServiceContract  $carServiceContract
     * @param  ClientServiceContract  $clientServiceContract
     * @param  CorporateClientServiceContract  $corporateClientServiceContract
     * @param  OrderServiceContract  $orderServiceContract
     */
    public function __construct(
        protected CompanyServiceContract $companyServiceContract,
        protected CarServiceContract $carServiceContract,
        protected ClientServiceContract $clientServiceContract,
        protected CorporateClientServiceContract $corporateClientServiceContract,
        protected OrderServiceContract $orderServiceContract
    ) {
    }

    /**
     * @return ResponseFactory|Factory|Response|View
     */
    public function indexShow(): Factory|Response|View|ResponseFactory
    {
        $staticInfo = $this->companyServiceContract->getData();

        if (!$staticInfo) {
            return response(trans('messages.something_went_wrong'), 400);
        }

        $staticInfo['user'] = user();

        return view('admin-corporate.index', ['staticInfo' => $staticInfo]);
    }

    /**
     * @return ResponseFactory|Response
     */
    public function companyEntities(): Response|ResponseFactory
    {
        return response(['entities' => []], 200);
    }

    /**
     * @param  int  $company_id
     * @param  int|null  $client_id
     * @return ResponseFactory|Response
     */
    public function getCarClasses(int $company_id, int $client_id = null): Response|ResponseFactory
    {
        $classes = $this->carServiceContract->getCompanyCarClasses($company_id);

        if (!$classes) {
            return response(['message' => 'fwefwe'], 400);
        }

        return response([
            'car_classes' => $classes,
        ]);
    }

    /**
     * @return ResponseFactory|Response
     */
    public function getPaymentTypes(): Response|ResponseFactory
    {
        return response(['payment_types' => $this->orderServiceContract->getPaymentTypes()]);
    }

    /**
     * @param  CheckClientExistsRequest  $request
     * @return ResponseFactory|Response
     */
    public function checkClientExists(CheckClientExistsRequest $request): Response|ResponseFactory
    {
        $client = $this->clientServiceContract->getClientByPhone($request->phone);
        $company = $this->companyServiceContract->getCompanyByPhone($request->phone);
        $attached = isset($client->client_id) ? $this->corporateClientServiceContract->isClientAttached($client->client_id) : null;

        if ($attached) {
            $corporate_client = $this->corporateClientServiceContract->getCorporateClientData($client->client_id, $attached->company_id);
            return response(
                [
                    'client' => $corporate_client,
                    'company' => $company,
                    'registered' => $attached
                ]
            );
        }

        return response(
            [
                'client' => $client,
                'company' => $company,
                'registered' => $attached
            ]
        );
    }

    /**
     * @param $address
     * @param  Geo  $geo
     * @return Application|ResponseFactory|Response
     * @throws GeocodeException|GuzzleException|ReflectionException
     */
    public function getCoordinates($address, Geo $geo): Response|Application|ResponseFactory
    {
        $geoObject = $geo->geocode($address);


        if (isset($geoObject['GeoObjectCollection'], $geoObject['GeoObjectCollection']['featureMember'][0]) && is_array($geoObject)) {
            $coords = $geoObject
            ['GeoObjectCollection']
            ['featureMember'][0]
            ['GeoObject']
            ['Point']
            ['pos'];

            $coordsParts = explode(" ", $coords);

            return response(['lat' => $coordsParts[0], 'lut' => $coordsParts[1]], 200);
        }

        return response(['coords' => null], 200);
    }

    public function getCarOptions(): Response|Application|ResponseFactory
    {
        return response(['carOptions' => $this->orderServiceContract->getCarOptions()]);
    }
}
