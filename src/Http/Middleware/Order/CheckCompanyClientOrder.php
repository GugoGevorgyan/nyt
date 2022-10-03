<?php

namespace Src\Http\Middleware\Order;

use Src\Models\Corporate\Company;
use Closure;
use Illuminate\Http\Request;

class CheckCompanyClientOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $company = Company::where('admin_corporate_id', auth()->id())->first();
        $companyClient = $company->clients
            ->where('client_id', $request->passenger_id)
            ->where('phone', $request->phone)
            ->first();
        if ($companyClient) {
            $corporateClient = $companyClient->corporateClients->where('company_id', $request->company_id)->first();
            if ($corporateClient) {
                $carType = $corporateClient->carTypes->where('car_class_id', $request->car_class_id)->first() ?: false;
            }
            $checkClient = $company->clients->where('client_id', $request->passenger_id)->first() ?: false;
            $checkCompany = $company->company_id == $request->company_id ?: false;
        }
        if (!$companyClient || !$checkClient || !$checkCompany || !$carType) {
            return response(['message' => 'Invalid Data'], 500);
        }

        return $next($request);
    }
}
