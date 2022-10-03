<?php

declare(strict_types=1);

namespace Src\Services\Tariff;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use ServiceEntity\BaseService;
use Src\Core\Enums\ConstRedis;
use Src\Core\Enums\ConstTariffType;
use Src\Exceptions\Lexcept;
use Src\Models\Tariff\TariffDestination;
use Src\Models\Tariff\TariffRegionBehind;
use Src\Models\Tariff\TariffRegionCity;
use Src\Repositories\Area\AreaContract;
use Src\Repositories\City\CityContract;
use Src\Repositories\Company\CompanyContract;
use Src\Repositories\FranchiseRegion\FranchiseRegionContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\PaymentType\PaymentTypeContract;
use Src\Repositories\Region\RegionContract;
use Src\Repositories\Tariff\TariffContract;
use Src\Repositories\TariffRent\TariffRentContract;
use Src\Repositories\TariffRentAlt\TariffRentAltContract;
use Src\Repositories\TariffRentAlt\TariffRentAltContractContract;
use Src\Repositories\TariffType\TariffTypeContract;
use Src\Services\Car\CarServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Tariff\Traits\GetFromTariff;
use Src\Services\Tariff\Traits\GetOrderPrice;
use Src\Services\Tariff\Traits\Util;

/**
 * Class ParkService
 * @package Src\Services\Park
 */
final class TariffService extends BaseService implements TariffServiceContract
{
    use Util;
    use GetFromTariff;
    use GetOrderPrice;

    /**
     * DetectInitialTariff constructor.
     *
     * @param TariffContract $tariffContract
     * @param RegionContract $regionContract
     * @param CityContract $cityContract
     * @param PaymentTypeContract $paymentTypeContract
     * @param CompanyContract $companyContract
     * @param GeocodeServiceContract $geoService
     * @param AreaContract $areaContract
     * @param OrderShippedDriverContract $shippedContract
     * @param TariffTypeContract $tariffTypeContract
     * @param FranchiseRegionContract $franchiseRegionContract
     * @param CarServiceContract $carService
     * @param TariffRentContract $tariffRentContract
     */
    public function __construct(
        protected TariffContract $tariffContract,
        protected RegionContract $regionContract,
        protected CityContract $cityContract,
        protected PaymentTypeContract $paymentTypeContract,
        protected CompanyContract $companyContract,
        protected GeocodeServiceContract $geoService,
        protected AreaContract $areaContract,
        protected OrderShippedDriverContract $shippedContract,
        protected TariffTypeContract $tariffTypeContract,
        protected FranchiseRegionContract $franchiseRegionContract,
        protected CarServiceContract $carService,
        protected TariffRentContract $tariffRentContract,
        protected TariffRentAltContract $altContract
    ) {
    }

    /**
     * @inheritDoc
     */
    public function adminPaginate($request): LengthAwarePaginator
    {
        $per_page = isset($request['per_page']) && is_numeric($request['per_page']) ? $request['per_page'] : 25;
        $page = isset($request->page) && is_numeric($request->page) ? $request->page : 1;
        $search = isset($request->search) && null != $request->search ? $request->search : null;

        return $this->tariffContract
            ->withoutGlobalScopes()
            ->where(fn($q) => $q->where('tariffs.name', 'LIKE', '%' . $search . '%'))
            ->with([
                'cars',
                'tariff_type',
                'region',
                'current_tariff',
                'tariff_behind'
            ])
            ->orderBy('tariff_id', 'DESC')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @inheritDoc
     * @throws Lexcept
     */
    public function driverOnWayPriceCalculate($in_order_hash, $driver_id, $from_cord, $to_cord): array
    {
        $hash_order = $this->shippedContract
            ->where('in_order_hash', '=', $in_order_hash)
            ->where('driver_id', '=', $driver_id)
            ->with(['order' => fn(BelongsTo $query) => $query->select(['order_id', 'client_id', 'created_at'])])
            ->findFirst([$this->shippedContract->getKeyName(), 'driver_id', 'order_id']);

        $created_order = igbinary_unserialize($this->redis()->hMGet(ConstRedis::order_create_data($hash_order['order']['order_id']), ['order_data'])[0] ?? '');

        if (!$hash_order || !$created_order) {
            throw new Lexcept('Invalid Data In order payload', 400);
        }

        $options = [
            'payment_type' => $created_order['order']['payment_type_id'],
            'demands' => $created_order['order']['car_option']['ids'],
            'car_class' => $created_order['order']['car_class_id'],
            'payment_type_company' => (int)($created_order['corporate']['company_id'] ?? null),
            'time' => $hash_order['order']['created_at']->format('Y-m-d H:i:s'),
        ];

        $tariff = $this->getTariff($from_cord, $to_cord, $options);
        $price = $this->getPriceByTariff($tariff, $from_cord, $to_cord, $options);

        return compact('tariff', 'price');
    }

    /**
     * @inheritDoc
     * @throws Lexcept
     */
    public function getTariff(array $from, array $to = [], array $options = [], bool $is_rent = false): ?array
    {
        $this->setData($from, $to, $options, $is_rent);
        $this->isCompany($options['payment_type']);

        return $this->masterTariff();
    }

    /**
     * @param array $tariffs
     * @param array $from
     * @param array $to
     * @param array $options
     * @param bool $is_rent
     * @return array
     * @throws Lexcept
     */
    public function getPriceByTariff(array $tariffs, array $from, array $to, array $options, bool $is_rent = false): array
    {
        $this->setData($from, $to, $options, $is_rent);

        if ($this->isRent) {
            $price = $this->calculateRentPrice($tariffs);
        } elseif ($this->toCoordinates) {
            $price = $this->calculateOrderPrice($tariffs);
        } else {
            $price = $this->calculateInitialPrice($tariffs);
        }

        return $price;
    }

    /**
     * @inheritdoc
     */
    public function getRentTimesByData($class_id): array
    {
        $region_id = session('order.from_region_id') ?: null;
        $city_id = session('order.from_city_id') ?: null;

        return $this->tariffRentContract
            ->whereHas('tariff', fn(Builder $query) => $query
                ->where('car_class_id', '=', $class_id)
                ->where('tariff_type_id', '=', ConstTariffType::RENTAL()->getValue())
                ->where('status', '=', 1)
                ->whereJsonContains('region->ids', $region_id)
                ->whereJsonContains('city->ids', $city_id)
            )
            ->findAll(['tariff_rent_id', 'tariff_id', 'hours'])
            ->map(fn($item) => $item->hours)
            ->all();
    }

    /**
     * @inheritDoc
     */
    public function getRegionsCitiesTariffs($city)
    {
        $city_ids = array_map(static fn($id) => (int)$id, $city);

        return $this->tariffContract
            ->has('tariff_region')
            ->whereJsonContains('city->ids', $city_ids)
            ->findAll() ?: [];
    }

    /**
     * @return array
     */
    public function getTariffsForCompanies(): array
    {
        $region_cities = $this->franchiseRegionContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->with('franchise_cities')
            ->findAll();

        $tariffs = $this->getCompanyUnRegionalTariffs();

        foreach ($region_cities as $region_city) {
            $region_id = $region_city->region_id;
            $city_ids = $region_city->franchise_cities->pluck('city_id')->toArray();

            $tariffs = array_merge($tariffs, $this->getCompanyRegionalTariffs($region_id, $city_ids));
        }

        return $tariffs;
    }

    /**
     * @inheritDoc
     */
    public function getTariffTypes(): Collection
    {
        return $this->tariffTypeContract->findAll() ?: collect();
    }

    public function getAlternativeTariffs($car_class_id, $country_id)
    {
        return $this->tariffContract
            ->where('car_class_id', '=', $car_class_id)
            ->where('country_id', '=', $country_id)
            ->whereHasMorph('current_tariff', [TariffRegionCity::class, TariffRegionBehind::class, TariffDestination::class])
            ->findAll();
    }
}
