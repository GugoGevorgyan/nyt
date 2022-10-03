<?php

declare(strict_types=1);

namespace Src\Http\Middleware\Order;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Src\Models\Corporate\Company;
use Src\Repositories\CarClass\CarClassContract;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Company\CompanyContract;
use Src\Repositories\CorporateClient\CorporateClientContract;

/**
 * Class CheckCompanyClientScheduledOrder
 * @package Src\Http\Middleware\Order
 */
class CheckCompanyClientScheduledOrder
{
    /**
     * @param  ClientContract  $clientContract
     * @param  CompanyContract  $companyContract
     * @param  CorporateClientContract  $corporateClientContract
     */
    public function __construct(
        protected ClientContract $clientContract,
        protected CompanyContract $companyContract,
        protected CorporateClientContract $corporateClientContract,
        protected CarClassContract $carClassContract
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        $company = $this->companyContract
            ->whereHas('corporateAdmins', fn(Builder $query) => $query->where('admin_corporate_id', auth()->id()))
            ->findFirst();

        if (!$company) {
            return response(['message' => 'Invalid Admin data'], 500);
        }

        foreach ($request->all() as $query) {
            $company_client = $this->clientContract
                ->where('client_id', $query['passenger_id'])
                ->where('phone', $query['phone'])
                ->findFirst(['client_id']);

            if ($company_client) {
                $corporate_client = $this->corporateClientContract
                    ->where('company_id', '=', $query['company_id'])
                    ->where('client_id', '=', $query['passenger_id'])
                    ->findFirst();

                if ($corporate_client) {
                    $carType = $this->corporateClientContract
                        ->where('client_id', '=', $query['passenger_id'])
                        ->whereHas('carClasses', fn(Builder $query) => $query->where('car_class_id', $query['car_class_id']))
                        ->findFirst(['car_class_id'])
                        ?: false;
                }

                $check_client = $this->companyContract
                    ->whereHas('clients', fn(Builder $query) => $query->where('client_id', '=', $query['passenger_id']))
                    ->find($company->company_id)
                    ?: false;

                $checkCompany = $company->company_id === $query['company_id'] || false;
            }

            if (!$company_client || !$check_client || !$checkCompany || !($carType ?? false)) {
                return response(['message' => 'Invalid Data'], 500);
            }
        }

        return $next($request);
    }
}
