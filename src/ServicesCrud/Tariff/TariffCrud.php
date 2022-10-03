<?php

declare(strict_types=1);


namespace Src\ServicesCrud\Tariff;

use Exception;
use JsonException;
use Src\Models\Tariff\TariffDestination;
use Src\Models\Tariff\TariffRegionBehind;
use Src\Models\Tariff\TariffRegionCity;
use Src\Repositories\Area\AreaContract;
use Src\Repositories\CarClass\CarClassContract;
use Src\Repositories\City\CityContract;
use Src\Repositories\Company\CompanyContract;
use Src\Repositories\Franchisee\FranchiseContract;
use Src\Repositories\Region\RegionContract;
use Src\Repositories\Tariff\TariffContract;
use Src\Repositories\TariffBehind\TariffBehindContract;
use Src\Repositories\TariffDestination\TariffDestinationContract;
use Src\Repositories\TariffRegionsCities\TariffRegionsCitiesContract;
use Src\Repositories\TariffRent\TariffRentContract;
use Src\Repositories\TariffRentAlt\TariffRentAltContract;
use Src\Repositories\TariffType\TariffTypeContract;
use Src\Scopes\TariffScope;
use Src\Services\Car\CarServiceContract;
use Src\ServicesCrud\BaseCrud;
use Throwable;
use function is_array;

/**
 * Class TariffCrud
 * @package Src\Services\Tariff\Crud
 */
class TariffCrud extends BaseCrud implements TariffCrudContract
{
    /**
     * TariffService constructor.
     * @param TariffContract $tariffContract
     * @param CarClassContract $carClassContract
     * @param TariffTypeContract $tariffTypeContract
     * @param CompanyContract $companyContract
     * @param RegionContract $regionContract
     * @param FranchiseContract $franchiseContract
     * @param CityContract $cityContract
     * @param CarServiceContract $carServiceContract
     * @param AreaContract $areaContract
     * @param TariffBehindContract $tariffBehindContract
     * @param TariffRegionsCitiesContract $tariffRegionsCitiesContract
     * @param TariffDestinationContract $tariffDestinationContract
     * @param TariffRentContract $tariffRentContract
     */
    public function __construct(
        protected TariffContract $tariffContract,
        protected CarClassContract $carClassContract,
        protected TariffTypeContract $tariffTypeContract,
        protected CompanyContract $companyContract,
        protected RegionContract $regionContract,
        protected FranchiseContract $franchiseContract,
        protected CityContract $cityContract,
        protected CarServiceContract $carServiceContract,
        protected AreaContract $areaContract,
        protected TariffBehindContract $tariffBehindContract,
        protected TariffRegionsCitiesContract $tariffRegionsCitiesContract,
        protected TariffDestinationContract $tariffDestinationContract,
        protected TariffRentContract $tariffRentContract,
        protected TariffRentAltContract $altContract
    ) {
    }

    /**
     * @param $tariff
     * @return bool|mixed
     * @throws Throwable
     */
    public function createTariff($tariff): bool
    {
        $tariff_data = [
            'name' => $tariff['name'],
            'car_class_id' => $tariff['car_class_id'],
            'tariff_type_id' => $tariff['tariff_type_id'],
            'country_id' => $tariff['country_id'] ?? null,
            'region' => $tariff['region'] && is_array($tariff['region']) ? decode(['ids' => $tariff['region']]) : decode(['ids' => []]),
            'city' => isset($tariff['city_ids']) && is_array($tariff['city_ids']) && $tariff['city_ids'][0] ? decode(['ids' => $tariff['city_ids']]) : decode(['ids' => []]),
            'payment_type_id' => $tariff['payment_type_id'],
            'paid_parking_client' => $tariff['paid_parking_client'],
            'tool_roads_client' => $tariff['tool_roads_client'],
            'is_default' => $tariff['is_default'],
            'status' => $tariff['status'],
            'free_wait_minutes' => $tariff['free_wait_minutes'],
            'paid_wait_minute' => $tariff['paid_wait_minute'],
            'minimal_price' => $tariff['minimal_price'],
            'rounding_price' => $tariff['rounding_price'],
            'date_from' => $tariff['date_from'],
            'date_to' => $tariff['date_to'],
        ];

        $this->tariffContract->beginTransaction();

        try {
            $created_tariff = $this->tariffContract->create($tariff_data);
            $this->createTariffOption($tariff, $created_tariff);
            $this->tariffContract->commit();
            return true;
        } catch (Exception $e) {
            $this->tariffContract->rollBack();
            return false;
        }
    }

    /**
     * @param $request
     * @param $created_tariff
     * @return bool
     */
    public function createTariffOption($request, $created_tariff): bool
    {
        $result = false;

        switch ($request['option']) {
            case 'rent':
                $result = $this->createRentTariff($request['rent'], $created_tariff);
                break;
            case 'regions_cities':
                $result = $this->createRegionsCitiesTariff($request['regions_cities'], $created_tariff);
                break;
            case 'destination':
                $result = $this->createDestinationTariff($request['destination'], $created_tariff);
                break;
            default:
        }

        return $result;
    }

    /**
     * @param $data
     * @param $tariff
     * @return mixed
     */
    public function createRentTariff($data, $tariff): bool
    {
        dd($data);
        $data['tariff_id'] = $tariff->tariff_id;
        $data['sitting_fee'] = $data['sitting_fee'] ?? 0;
        $data['sit_fix_price'] = $data['sit_fix_price'] ?? 0;
        $data['sit_price_km'] = $data['sit_price_km'] ?? 0;
        $data['sit_price_minute'] = $data['sit_price_minute'] ?? 0;

        $option = $this->tariffRentContract->create($data);
        foreach ($data['rent_alt'] as $key => $value) {
            unset($value['tariff_rent_alt_id']);
            $value['rent_id'] = $option['tariff_rent_id'];
            $rentAlt_option = $this->altContract->create($value);
        }

        if (!$option && !$rentAlt_option) {
            return false;
        }
        $this->deleteOldOption($tariff);

        return $this->tariffContract
            ->withoutScope()
            ->where('tariff_id', '=', $tariff->tariff_id)
            ->update($tariff->tariff_id,
                [
                    'tariffable_id' => $option->{$option->getKeyname()},
                    'tariffable_type' => $option->getMap()
                ]);
    }

    /**
     * @param $tariff
     */
    public function deleteOldOption($tariff): void
    {
        if ($tariff->current_tariff) {
            $tariff->current_tariff->delete();
        }
    }

    /**
     * @param $data
     * @param $tariff
     * @return mixed
     */
    public function createRegionsCitiesTariff($data, $tariff): bool
    {
        $data['tariff_id'] = $tariff->tariff_id;
        $data['sit_fix_price'] = $data['sit_fix_price'] ?? 0;
        $data['sit_price_km'] = $data['sit_price_km'] ?? 0;
        $data['sit_price_minute'] = $data['sit_price_minute'] ?? 0;
        $data['price_km'] = $data['price_km'] ?? 0;
        $data['price_min'] = $data['price_min'] ?? 0;

        $option = $this->tariffRegionsCitiesContract->create($data);

        if (!$option) {
            return false;
        }

        if ($data['tariff_behind']) {
            $data['tariff_behind']['tariff_region_id'] = $option->tariff_region_city_id;
            $data['tariff_behind']['tariff_id'] = $option->tariff_id;
            $this->createRegionBehindTariff($data['tariff_behind']);
        }

        $this->deleteOldOption($tariff);

        return $this->tariffContract
            ->withoutScope()
            ->update($tariff->tariff_id,
                [
                    'tariffable_id' => $option->{$option->getKeyname()},
                    'tariffable_type' => $option->getMap()
                ]);
    }

    /**
     * @param $data
     * @return false|void
     */
    protected function createRegionBehindTariff($data)
    {
        $data['sitting_fee'] = $data['sitting_fee'] ?? 0;
        $data['sit_fix_price'] = $data['sit_fix_price'] ?? 0;
        $data['sit_price_km'] = $data['sit_price_km'] ?? 0;
        $data['sit_price_minute'] = $data['sit_price_minute'] ?? 0;
        $data['price_km'] = $data['price_km'] ?? 0;
        $data['price_min'] = $data['price_min'] ?? 0;

        $option = $this->tariffBehindContract->create($data);

        if (!$option) {
            return false;
        }
    }

    /**
     * @param $data
     * @param $tariff
     * @return mixed
     */
    public function createDestinationTariff($data, $tariff): bool
    {
        $data['tariff_id'] = $tariff->tariff_id;
        $data['sitting_fee'] = (int)($data['sitting_fee'] ?? 0);
        $data['sit_fix_price'] = (int)($data['sit_fix_price'] ?? 0);
        $data['sit_price_km'] = (int)($data['sit_price_km'] ?? 0);
        $data['sit_price_minute'] = (int)($data['sit_price_minute'] ?? 0);
        $option = $this->tariffDestinationContract->create($data);

        $this->deleteOldOption($tariff);

        if (!$option) {
            return false;
        }

        return $this->tariffContract
            ->withoutScope()
            ->update($tariff->tariff_id,
                [
                    'tariffable_id' => $option->{$option->getKeyname()},
                    'tariffable_type' => $option->getMap()
                ]);
    }

    /**
     * @param $tariff_id
     * @return null|object
     */
    public function getTariffById($tariff_id): ?object
    {
        $data = $this->tariffContract
            ->withoutGlobalScopes([TariffScope::class])
            ->with([
                'current_tariff' => fn($query) => [
                    $query->with([
                        'alt_behind',
                        'alt_region',
                        'alt_destination',
                    ])
                ]
            ])
            ->find($tariff_id);

        if ($data['current_tariff'] && count($data['current_tariff']['alt_behind']) > 0) {
            foreach ($data['current_tariff']['alt_destination'] as $key => $value) {
                $altable = $this->tariffContract
                    ->where('tariffable_id', '=', $data['current_tariff']['alt_behind'][$key]['tariff_region_behind_id'])
                    ->findFirst(['tariff_id', 'name', 'tariffable_id']);
                $data['current_tariff']['alt_behind'][$key]['name'] = $altable['name'];
                $data['current_tariff']['alt_behind'][$key]['tariffable_id'] = $altable['tariffable_id'];
            }
        }
        if ($data['current_tariff'] && count($data['current_tariff']['alt_region']) > 0) {
            foreach ($data['current_tariff']['alt_region'] as $key => $value) {
                $altable = $this->tariffContract
                    ->where('tariffable_id', '=', $data['current_tariff']['alt_region'][$key]['tariff_region_city_id'])
                    ->findFirst(['tariff_id', 'name', 'tariffable_id']);
                $data['current_tariff']['alt_region'][$key]['name'] = $altable['name'];
                $data['current_tariff']['alt_region'][$key]['tariffable_id'] = $altable['tariffable_id'];

            }
        }
        if ($data['current_tariff'] && count($data['current_tariff']['alt_destination']) > 0) {
            foreach ($data['current_tariff']['alt_destination'] as $key => $value) {
                $altable = $this->tariffContract
                    ->where('tariffable_id', '=', $data['current_tariff']['alt_destination'][$key]['tariff_destination_id'])
                    ->findFirst(['tariff_id', 'name', 'tariffable_id']);
                $data['current_tariff']['alt_destination'][$key]['name'] = $altable['name'];
                $data['current_tariff']['alt_destination'][$key]['tariffable_id'] = $altable['tariffable_id'];
            }
        }
        $alternatives = $this->tariffContract->where('country_id', '=', $data['country_id'])
            ->where('car_class_id', '=', $data['car_class_id'])
            ->whereHasMorph('current_tariff', [TariffRegionCity::class, TariffRegionBehind::class, TariffDestination::class])
            ->findAll();
        $data['all_alternatives'] = count($alternatives) > 0 ? $alternatives : null;
//dd($data->toArray());
        return $data;
    }

    /**
     * @param $tariff_id
     * @param $request
     * @return bool
     * @throws JsonException
     * @throws Exception
     */
    public function updateTariff($tariff_id, $request): bool
    {
//        dd($tariff_id, $request);
        $tariff_data = [
            'name' => $request['name'],
            'car_class_id' => $request['car_class_id'],
            'tariff_type_id' => $request['tariff_type_id'],
            'country_id' => $request['country_id'] ?? null,
            'region' => $request['region'] && is_array($request['region']) ? decode(['ids' => $request['region']]) : decode(['ids' => []]),
            'city' => isset($request['city_ids']) && is_array($request['city_ids']) && $request['city_ids'][0] ? decode(['ids' => $request['city_ids']]) : decode(['ids' => []]),
            'payment_type_id' => $request['payment_type_id'],
            'paid_parking_client' => $request['paid_parking_client'],
            'tool_roads_client' => $request['tool_roads_client'],
            'is_default' => $request['is_default'],
            'status' => $request['status'],
            'free_wait_minutes' => $request['free_wait_minutes'],
            'paid_wait_minute' => $request['paid_wait_minute'],
            'minimal_price' => $request['minimal_price'],
            'rounding_price' => $request['rounding_price'],
            'date_from' => $request['date_from'],
            'date_to' => $request['date_to'],
        ];

        $this->tariffContract->beginTransaction();

        try {
            $tariff = $this->tariffContract->withoutGlobalScopes([TariffScope::class])->findOrFail($tariff_id);
            $this->tariffContract->update($tariff_id, $tariff_data);

            $this->updateTariffOption($request, $tariff);
            $this->tariffContract->commit();
            return true;
        } catch (Exception $e) {
            $this->tariffContract->rollBack();
            return false;
        }
    }

    /**
     * @param $request
     * @param $tariff
     * @return mixed
     */
    protected function updateTariffOption($request, $tariff): mixed
    {
        switch ($request['option']) {
            case 'rent':

                $data = $request['rent'];
                return isset($data['tariff_rent_id'])
                    ? $this->updateRentTariff($data)
                    : $this->createRentTariff($data, $tariff);
            case 'regions_cities':

                $data = $request['regions_cities'];
                return isset($data['tariff_region_city_id'])
                    ? $this->updateRegionsCitiesTariff($data)
                    : $this->createRegionsCitiesTariff($data, $tariff);
            case 'destination':

                $data = $request['destination'];
                return isset($data['tariff_destination_id'])
                    ? $this->updateDestinationTariff($data)
                    : $this->createDestinationTariff($data, $tariff);
            default:
        }

        return false;
    }

    /**
     * @param $data
     * @return bool|object
     */
    protected function updateRentTariff($data): object|bool
    {
        $rent = $this->tariffRentContract->findOrFail($data['tariff_rent_id']);

        if (!$rent) {
            return false;
        }

        foreach ($data['rent_alt'] as $key => $value) {
            if ($value['tariff_rent_alt_id']) {
                $rentAlt_update = $this->altContract->update($value['tariff_rent_alt_id'], $value);
            } else {
                $rentAlt_update = $this->altContract->create($value);
            }
        }
        $rent_update = $this->tariffRentContract->update($data['tariff_rent_id'], $data);

        if (!$rent_update && !$rentAlt_update) {
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function updateRegionsCitiesTariff($data): mixed
    {
        $option = $this->tariffRegionsCitiesContract->findOrFail($data['tariff_region_city_id']);

        if ($data['tariff_behind'] ?? false) {
            $region_behind = $this->getRegionBehindRow($data['tariff_region_city_id']);

            if ($region_behind) {
                $region_behind->update($data['tariff_behind']);
            } else {
                $data['tariff_behind']['tariff_region_id'] = $data['tariff_region_city_id'];
                $data['tariff_behind']['tariff_id'] = $data['tariff_id'];
                $this->createRegionBehindTariff($data['tariff_behind']);
            }
        } else {
            $region_behind = $this->getRegionBehindRow($data['tariff_region_city_id']);

            if ($region_behind) {
                $this->tariffBehindContract->delete($region_behind['tariff_region_behind_id']);
            }
        }

        return $option->update($data);
    }

    /**
     * @param $tariff_region_id
     * @return object|null
     */
    protected function getRegionBehindRow($tariff_region_id): ?object
    {
        return $this->tariffBehindContract
            ->where('tariff_region_id', '=', $tariff_region_id)
            ->findFirst();
    }

    /**
     * @param $data
     * @return bool|object
     */
    public function updateDestinationTariff($data): object|bool
    {
        $destination = $this->tariffDestinationContract->findOrFail($data['tariff_destination_id']);

        if (!$destination) {
            return false;
        }

        return $this->tariffDestinationContract->update($data['tariff_destination_id'], $data);
    }

    /**
     * @inheritDoc // @todo fixing query for caching
     */
    public function copyTariff($request, $tariff_id)
    {
        $tariff = $this->tariffContract->with(['current_tariff', 'tariff_type'])->findOrFail($tariff_id);
        $option = $tariff->current_tariff->replicate();
        $copied = $tariff->replicate();

        $copied->name = $request['name'];
        $copied->car_class_id = $request['car_class_id'];
        $copied->minimal_price = $request['minimal_price'];
        $copied->save();

        $option->tariff_id = $copied->tariff_id;

        switch ($tariff->tariff_type->type) {
            case 1:
                $option->price_min = $request['price_min'];
                break;
            case 2:
                $option->price_km = $request['price_km'];
                break;
            case 3:
                $option->price_min = $request['price_min'];
                $option->price_km = $request['price_km'];
                break;
            default:
        }

        $option->save();

        return $copied->update(['tariffable_id' => $option->getKey()]);
    }

    /**
     * @param $tariff_id
     * @return mixed|null
     */
    public function deleteTariff($tariff_id): ?bool
    {
        $tariff = $this->tariffContract->withoutGlobalScopes([TariffScope::class])->findOrFail($tariff_id);

        return $tariff ? $this->tariffContract->delete($tariff_id) : false;
    }
}
