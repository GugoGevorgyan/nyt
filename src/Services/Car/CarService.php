<?php

declare(strict_types=1);


namespace Src\Services\Car;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use JsonException;
use ServiceEntity\BaseService;
use Src\Core\Additional\Guzzle as GuzzleClient;
use Src\Core\Enums\ConstTariffType;
use Src\Core\Traits\OauthTrait;
use Src\Events\UpdatedMechanicInfo;
use Src\Models\Tariff\TariffRent;
use Src\Repositories\CarClass\CarClassContract;
use Src\Repositories\CarOption\CarOptionContract;
use Src\Repositories\CarStatus\CarStatusContract;
use Src\Repositories\City\CityContract;
use Src\Repositories\ClassOption\ClassOptionContract;
use Src\Repositories\Region\RegionContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;
use Src\Repositories\Tariff\TariffContract;
use Src\Services\Geocode\GeocodeServiceContract;

/**
 * Class CarService
 * @package Src\Services\Car
 */
final class CarService extends BaseService implements CarServiceContract
{
    use OauthTrait;

    /**
     * CarReportService constructor.
     * @param  GuzzleClient  $client
     * @param  SystemWorkerContract  $systemWorkerContract
     * @param  CarOptionContract  $carOptionContract
     * @param  CarClassContract  $carClassContract
     * @param  GeocodeServiceContract  $geoService
     * @param  RegionContract  $regionContract
     * @param  CityContract  $cityContract
     * @param  TariffContract  $tariffContract
     * @param  CarStatusContract  $carStatusContract
     * @param  ClassOptionContract  $classOptionContract
     */
    public function __construct(
        protected GuzzleClient $client,
        protected SystemWorkerContract $systemWorkerContract,
        protected CarOptionContract $carOptionContract,
        protected CarClassContract $carClassContract,
        protected GeocodeServiceContract $geoService,
        protected RegionContract $regionContract,
        protected CityContract $cityContract,
        protected TariffContract $tariffContract,
        protected CarStatusContract $carStatusContract,
        protected ClassOptionContract $classOptionContract
    ) {
    }

    /**
     * @inheritdoc
     */
    public function getCompanyCarClasses(int $company_id, int $client_id = null): Collection
    {
        return $this->carClassContract
            ->when($client_id, fn(Builder $query) => $query
                ->whereHas('corporateClients', fn($query) => $query->where('client_id', '=', $client_id)))
            ->whereHas('tariffs', fn(Builder $query) => $query
                ->whereHas('companies', fn(Builder $query) => $query->where('companies.company_id', '=', $company_id)))
            ->findAll(['car_class_id', 'class_name', 'image']);
    }

    /**
     * @inheritDoc
     */
    public function getFranchiseCarClasses(int $franchise_id, int $client_id = null): Collection
    {
        return $this->carClassContract
            ->when($client_id, fn(Builder $query) => $query
                ->whereHas('corporateClients', fn($query) => $query->where('client_id', '=', $client_id)))
            ->whereHas('tariffs', fn(Builder $query) => $query
                ->whereHas('companies', fn(Builder $query) => $query->where('companies.franchise_id', '=', $franchise_id)))
            ->findAll(['car_class_id', 'class_name', 'image']);
    }

    /**
     * @return Collection
     */
    public function getCarStatuses(): Collection
    {
        return $this->carStatusContract->findAll();
    }

    /**
     * @return Collection
     */
    public function getCarClasses(): Collection
    {
        return $this->carClassContract->findAll();
    }

    /**
     * @param $worker_data
     * @return array|null
     * @throws GuzzleException
     * @throws JsonException
     */
    public function mechanicRefreshToken($worker_data): ?array
    {
        $mechanic = $this->systemWorkerContract->where('nickname', '=', $worker_data['username'])->findFirst();

        if (!$mechanic) {
            return null;
        }

        $bearer_token = $this->refreshOauthToken($worker_data);

        return [$mechanic, $bearer_token];
    }

    /**
     * @param $request
     * @return bool|null
     */
    public function logoutWorker($request): ?bool
    {
        if (Auth::check()) {
            $request->user()->token()->revoke();

            return true;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function updateInfo($request): bool
    {
        $result = $this->systemWorkerContract->update(auth()->id(), $request);
        $name = $this->systemWorkerContract->find(auth()->id())->name;
        $franchise_id = $this->systemWorkerContract->find(auth()->id())->franchise_id;
        $admin = $this->systemWorkerContract->where((string)[['is_admin', '=', 1], ['franchise_id', '=', $franchise_id]])->findFirst();

        UpdatedMechanicInfo::dispatch($name, $admin->email, $admin->name);

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getAllOptions(): Collection
    {
        return $this->carOptionContract->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getCarClassesWithMinPrice(array $cords, $company_id = null, array $options = [], bool $is_rent = false): ?Collection
    {
        $region = null;
        $city = null;
        $locality_sess = session('locality') ?? [];
        $company_id = $company_id ?? session('order.company');

        $client_location_data = $locality_sess
            ? ['locality' => $locality_sess['locality'], 'province' => $locality_sess['province']]
            : $this->geoService->getRightGeocode($cords);

        if ($client_location_data['province']) {
            $region = $this->regionContract
                ->with(['country' => fn(BelongsTo $query) => $query->select(['country_id', 'currency'])])
                ->where('name', '=', $client_location_data['province'])
                ->findFirst(['region_id', 'country_id', 'name']);
        }

        if ($client_location_data['locality']) {
            $city = $this->cityContract
                ->where('name', '=', $client_location_data['locality'])
                ->with(['country' => fn(BelongsTo $query) => $query->select(['country_id', 'currency'])])
                ->findFirst(['city_id', 'country_id', 'name']);
        }

        return $this->tariffContract
            ->when($region, fn(Builder $query) => $query->whereJsonContains('region->ids', $region->region_id))
            ->when($city, fn(Builder $query) => $query
                ->where(fn(Builder $query) => $query
                    ->whereJsonContains('city->ids', $city->city_id)
                    ->orWhereJsonLength('city->ids', '=')
                )
            )
            ->when(!$company_id, fn(Builder $query) => $query->where('is_default', '=', true))
            ->when($company_id, fn(Builder $query) => $query
                ->where('is_default', '=', false)
                ->whereHas('companies', fn(Builder $query) => $query->where('companies.company_id', '=', $company_id))
            )
            ->when($is_rent, fn(Builder $query) => $query
                ->whereHasMorph('current_tariff', (new TariffRent())->getMap(), fn(Builder $query) => $query->where('hours', '=', $options['rent_time'])))
            ->where('tariff_type_id', $is_rent ? '=' : '!=', ConstTariffType::RENTAL()->getValue())
            ->with([
                'cars' => fn(BelongsTo $query) => $query
                    ->when($company_id, fn($query) => $query
                        ->whereHas('corporateClients', fn($query) => $query->where('corporate_clients.client_id', '=', get_user_id())))
                    ->select(['car_class_id', 'class_name', 'name', 'image'])
            ])
            ->groupBy('car_class_id')
            ->orderBy('car_class_id')
            ->findAll(['tariff_id', 'car_class_id', 'region', 'city', 'minimal_price'])
            ->transform(
                static function ($item) use ($region, $city) {
                    if ($item->cars) {
                        $item->cars->tariff_id = $item->tariff_id;
                        $item->cars->coin = $item->minimal_price;
                        $item->cars->currency = (!$region && !$city) ?? session('app_system.currency') ?: $region->country->currency ?? $city->country->currency;
                    }

                    return $item->cars;
                }
            )
            ->filter();
    }

    /**
     * @inheritDoc
     */
    public function carClassByCompany($company_id, $client_id): Collection
    {
        return $this->tariffContract
            ->when($company_id, fn(Builder $query) => $query
                ->where('is_default', '=', false)
                ->whereHas('companies', fn(Builder $query) => $query->where('companies.company_id', '=', $company_id))
            )
            ->with([
                'cars' => fn(BelongsTo $query) => $query
                    ->whereHas('corporateClients', fn($query) => $query->where('corporate_clients.client_id', '=', $client_id))
                    ->select(['car_class_id', 'class_name', 'image'])
            ])
            ->groupBy('tariffs.car_class_id')
            ->findAll(['tariff_id', 'car_class_id', 'region', 'city', 'minimal_price'])
            ->transform(
                static function ($item) {
                    if ($item->cars) {
                        $item->cars->coin = $item->minimal_price;
                        $item->cars->currency = 'RUB';
                    }

                    return $item->cars;
                }
            )
            ->filter();
    }

    /**
     * @param  array  $options
     * @return float|null
     */
    public function calculateOrderOptions(array $options): ?float
    {
        if (!empty($options)) {
            $demands = $this->carOptionContract->findWhereIn(['car_option_id', $options], ['car_option_id', 'price']);
            $price = null;

            foreach ($demands as $demand) {
                $price += $demand->price;
            }

            return $price;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getCarOptions(array $values = []): ?Collection
    {
        return $this->carOptionContract->findAll(['*']) ?: null;
    }

    /**
     * @inheritDoc
     */
    public function getOptionPrice($option_id, $class_id, $tariff_id = null): float|string|null
    {
        return $this->classOptionContract
                ->where('option_id', '=', $option_id)
                ->where('class_id', '=', $class_id)
                ->when($tariff_id, fn(Builder $query) => $query->where('tariff_id', '=', $tariff_id))
                ->findFirst(['option_id', 'class_id', 'tariff_id', 'price'])
                ->price
            ?? $this->carOptionContract
                ->where('car_option_id', '=', $option_id)
                ->findFirst(['car_option_id', 'price'])
                ->price
            ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getOptions($class_id, $tariff_id = null): array
    {
        return $this->carOptionContract
            ->whereHas('class_option_tariff', fn(Builder $query) => $query
                ->where('class_id', '=', $class_id)
                ->where('tariff_id', '=', $tariff_id)
            )
            ->with([
                'class_option_tariff' => fn(BelongsTo $query) => $query
                    ->where('class_id', '=', $class_id)
                    ->where('tariff_id', '=', $tariff_id)
                    ->select(['price', 'class_id', 'tariff_id', 'option_id'])
            ])
            ->findAll(['car_option_id', 'option', 'name', 'value'])
            ->each(function ($item) {
                $item->price = $item->class_option_tariff->price ?? 0.0;
                unset($item->class_option_tariff);
            })
            ->toArray();
    }
}
