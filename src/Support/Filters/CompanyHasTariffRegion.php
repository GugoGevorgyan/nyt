<?php

declare(strict_types=1);


namespace Src\Support\Filters;


use Src\Services\Company\CompanyServiceContract;

/**
 * Class CompanyHasTariffRegion
 * @package Src\Filters
 */
class CompanyHasTariffRegion extends BaseFilter
{
    /**
     * @return mixed
     */
    public function filter()
    {
        $this->app->extend(
            'company_has_tariff_region', static function ($attribute, $value, $coordinate, $validator) {
            if (!empty($coordinate) && $value) {
                $service_contract = app(CompanyServiceContract::class);
                return $service_contract->hasCompanyIsCoordinate($coordinate, $value);
            }

            return true;
        });
    }
}
