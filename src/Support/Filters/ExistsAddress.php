<?php

declare(strict_types=1);


namespace Src\Support\Filters;


use Src\Models\Corporate\Company;
use Src\Models\Corporate\CorporateClient;
use Illuminate\Support\Arr;


/**
 * Class ExistsAddress
 * @package Src\Support\Filters
 */
class ExistsAddress extends BaseFilter
{
    public function filter()
    {
        $this->app->extend('exists_address', static function ($attribute, $value, $parameters, $validator) {
            $client = CorporateClient::find(Arr::first($parameters))->client;
            $company = Company::where('admin_corporate_id', get_user_id())->first();
            $address = $client->addresses()->where('company_id', $company->company_id)->where('value', $value)->first();
            return $address ? false : true;
        });
    }
}
