<?php

declare(strict_types=1);

namespace Src\Http\Controllers\App;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use JetBrains\PhpStorm\NoReturn;
use JsonException;
use Src\Core\Additional\Devicer;
use Src\Core\Enums\ConstGuards;
use Src\Http\Controllers\Controller;
use Src\Http\Resources\App\GetInitResource;
use Src\Http\Resources\App\OnlineResource;
use Src\Repositories\Airport\AirportContract;
use Src\Repositories\Metro\MetroContract;
use Src\Repositories\RailwayStation\RailwayStationContract;
use Src\Services\Auth\AuthServiceContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Throwable;

/**
 * Class IndexController
 * @package Src\Http\Controllers\ClientWeb
 */
class IndexController extends Controller
{
    /**
     * IndexController constructor.
     * @param  AuthServiceContract  $authService
     * @param  GeocodeServiceContract  $geoService
     * @param  ClientServiceContract  $clientService
     * @param  AirportContract  $airportContract
     * @param  MetroContract  $metroContract
     * @param  RailwayStationContract  $stationContract
     * @throws Throwable
     */
    public function __construct(
        protected AuthServiceContract $authService,
        protected GeocodeServiceContract $geoService,
        protected ClientServiceContract $clientService,
        protected AirportContract $airportContract,
        protected MetroContract $metroContract,
        protected RailwayStationContract $stationContract,
    ) {
//        $tariff = app(TariffContract::class)
//            ->with(['current_tariff' => fn($query)=>$query->with('rent_alts')])
//            ->find(75);
//        dd($tariff);

//        $user = app(ClientContract::class)->with('addresses')->findFirst();
//        dd($user);
//        $user->setRelation('fewfwe', 'fewfewfw');
        //        app(ClientContract::class)
//            ->create([
//                'phone' => 'fewffew',
//                'name' => 'fewfew',
//                'surname' => 'fweffwe',
//                'patronymic' => 'fwefew',
//                'email' => 'wefewf@mail.com',
//                'password' => \Hash::make('fwefewfw'),
//                'remember_password' => \Hash::make('fwefewfw'),
//                'addresses' => [
//                    [
//                        'name' => 'fewfewfewfew',
//                        'short_address' => 'fwefew',
//                        'address' => 'dewfewf',
//                        'lat' => '99.99999999',
//                        'lut' => '999.99999999',
//                        'favorite' => true
//                    ],
//                    [
//                        'name' => 'fewfewfewfew',
//                        'short_address' => 'fwefew',
//                        'address' => 'dewfewf',
//                        'lat' => '99.99999999',
//                        'lut' => '999.99999999',
//                        'favorite' => true
//                    ]],
//            ], true);

        //        dd(app(DriverContract::class)->with('preorders')->find(1));
//        dd(json_decode(\redis('default')->zrange('queues:long:delayed', 0, -1)[0]));
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|\Illuminate\Contracts\View\View|RedirectResponse
     * @throws JsonException
     * @throws Exception
     */
    public function showIndexPage(Request $request): \Illuminate\Contracts\View\View|Factory|RedirectResponse|Application
    {
        $this->authService->clientHasAuthGenerateHash();
        $location = geoip($request->ip());
        $priority_users = [ConstGuards::CLIENTS_WEB()->getValue(), ConstGuards::BEFORE_CLIENTS_WEB()->getValue()];
        $client_companies = $this->authService->detectUser($request, $priority_users);

        if ((new Devicer())->isMobile()) {
            return redirect()->route('mobile_index');
        }

        return view(
            'app.index',
            [
                'client' => $client_companies ? $client_companies['client'] ?? null : null,
                'companies' => $client_companies ? $client_companies['companies'] ?? [] : [],
                'location' => json_encode([$location->lat, $location->lon], JSON_THROW_ON_ERROR),
            ]
        );
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|View|RedirectResponse
     * @throws JsonException
     * @throws Exception
     */
    public function mobileIndex(Request $request): Factory|View|RedirectResponse|Application
    {
        $this->authService->clientHasAuthGenerateHash();
        $location = geoip($request->ip());
        $client_companies = $this->authService->detectUser($request);

        if ((new Devicer())->isDesktop()) {
            return redirect()->route('homepage');
        }

        return view(
            'app.mobile.index',
            [
                'client' => $client_companies ? $client_companies['client'] ?? null : null,
                'companies' => $client_companies ? $client_companies['companies'] ?? [] : [],
                'location' => json_encode([$location->lat, $location->lon], JSON_THROW_ON_ERROR),
            ]
        );
    }

    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function mobileAuth(): Factory|View|RedirectResponse|Application
    {
        if (isset(user()->client_id)) {
            return redirect()->route('mobile_index');
        }

        return \view('app.mobile.auth');
    }

    /**
     * @param  Request  $request
     * @param  null  $lat
     * @param  null  $lut
     * @return Application|ResponseFactory|Response|GetInitResource
     * @throws Exception
     */
    #[NoReturn] public function getOrderInfo(Request $request, $lat = null, $lut = null): Response|GetInitResource|Application|ResponseFactory
    {
        $user = user();
        $country_lat = $lat ?? geoip($request->ip());
        $cords = $lut ? compact('lat', 'lut') : ['lat' => $country_lat->lat, 'lut' => $country_lat->lon];

        $info = $this->clientService->getOrderInfo($user, $cords);
//        $info['phone_mask'] = $this->clientService->getPhoneMask($user, $country_lat, $lut);
        $info['location'] = $cords;

        if (!$info) {
            return response(['message' => 'FAIL'], 400);
        }

        return new GetInitResource($info);
    }


    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response|OnlineResource
     */
    public function online(Request $request)
    {
        $user = user();

        if (!$user) {
            return response(['message' => 'Failed'], 401);
        }

        $result = $this->clientService->clientOnlineMaster($user, $request->status);
        $result['state'] = $this->clientService->getRealState($user);
        $result['preorder'] = $this->clientService->getPreorderLimit(get_user_id());

        if (!$result) {
            return response(['message' => 'Failed']);
        }

        return isset($result['order'])
            ? new OnlineResource($result)
            : response(['message' => 'OK', '_payload' => $result]);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function getTransports(): Response|Application|ResponseFactory
    {
        $airports = $this->airportContract->findAll(['airport_id', 'name', 'lat', 'lut']);
        $metros = $this->metroContract->findAll(['metro_id', 'name', 'lat', 'lut']);
        $stations = $this->stationContract->findAll(['railway_station_id', 'name', 'lat', 'lut']);

        return response(['message' => 'ok', '_payload' => compact('airports', 'metros', 'stations')]);
    }
}
